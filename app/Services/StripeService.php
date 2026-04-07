<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripeService
{
    private ?StripeClient $stripe = null;
    private ?string $secretKey = null;

    public function __construct()
    {
        $secret = config('services.stripe.secret');
        if (is_string($secret) && ! blank($secret)) {
            $this->secretKey = $secret;
        }
    }

    private function client(): StripeClient
    {
        if ($this->stripe instanceof StripeClient) {
            return $this->stripe;
        }

        if (! is_string($this->secretKey) || blank($this->secretKey)) {
            throw new \RuntimeException('Stripe secret key is not configured.');
        }

        Stripe::setApiKey($this->secretKey);
        $this->stripe = new StripeClient($this->secretKey);

        return $this->stripe;
    }

    public function createPaymentIntent(Order $order): PaymentIntent
    {
        $amountInCents = (int) ($order->grand_total * 100);

        $paymentIntent = $this->client()->paymentIntents->create([
            'amount' => $amountInCents,
            'currency' => strtolower($order->currency),
            'payment_method_types' => ['card'],
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'order_uuid' => $order->uuid,
            ],
        ]);

        return $paymentIntent;
    }

    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        return $this->client()->paymentIntents->retrieve($paymentIntentId);
    }

    public function handleWebhook(array $payload): void
    {
        $eventType = $payload['type'] ?? null;
        $paymentIntent = $payload['data']['object'] ?? null;

        if (! $eventType || ! $paymentIntent) {
            Log::warning('Invalid Stripe webhook payload', ['payload' => $payload]);

            return;
        }

        $paymentIntentId = $paymentIntent['id'] ?? null;
        $orderId = $paymentIntent['metadata']['order_id'] ?? null;

        if (! $paymentIntentId || ! $orderId) {
            Log::warning('Missing payment intent ID or order ID in webhook', [
                'payment_intent_id' => $paymentIntentId,
                'order_id' => $orderId,
            ]);

            return;
        }

        $order = Order::find($orderId);

        if (! $order) {
            Log::warning('Order not found for webhook', ['order_id' => $orderId]);

            return;
        }

        $payment = Payment::where('provider_reference', $paymentIntentId)->first();

        if (! $payment) {
            Log::warning('Payment not found for webhook', ['payment_intent_id' => $paymentIntentId]);

            return;
        }

        match ($eventType) {
            'payment_intent.succeeded' => $this->handlePaymentSucceeded($payment, $paymentIntent),
            'payment_intent.payment_failed' => $this->handlePaymentFailed($payment, $paymentIntent),
            'payment_intent.canceled' => $this->handlePaymentCanceled($payment, $paymentIntent),
            default => Log::info('Unhandled Stripe webhook event', ['event_type' => $eventType]),
        };
    }

    private function handlePaymentSucceeded(Payment $payment, array $paymentIntent): void
    {
        $payment->update([
            'status' => 'succeeded',
            'method' => $paymentIntent['payment_method_types'][0] ?? null,
            'captured_at' => now(),
            'metadata' => array_merge($payment->metadata ?? [], [
                'stripe_payment_method' => $paymentIntent['payment_method'] ?? null,
                'stripe_charges' => $paymentIntent['charges']['data'] ?? [],
            ]),
        ]);

        $order = $payment->order;
        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        Log::info('Payment succeeded', [
            'payment_id' => $payment->id,
            'order_id' => $order->id,
        ]);
    }

    private function handlePaymentFailed(Payment $payment, array $paymentIntent): void
    {
        $lastPaymentError = $paymentIntent['last_payment_error'] ?? [];

        $payment->update([
            'status' => 'failed',
            'failure_code' => $lastPaymentError['code'] ?? null,
            'failure_message' => $lastPaymentError['message'] ?? null,
            'metadata' => array_merge($payment->metadata ?? [], [
                'stripe_error' => $lastPaymentError,
            ]),
        ]);

        $order = $payment->order;
        $order->update([
            'payment_status' => 'failed',
        ]);

        Log::warning('Payment failed', [
            'payment_id' => $payment->id,
            'order_id' => $order->id,
            'failure_code' => $lastPaymentError['code'] ?? null,
        ]);
    }

    private function handlePaymentCanceled(Payment $payment, array $paymentIntent): void
    {
        $payment->update([
            'status' => 'canceled',
            'metadata' => array_merge($payment->metadata ?? [], [
                'canceled_reason' => $paymentIntent['cancellation_reason'] ?? null,
            ]),
        ]);

        $order = $payment->order;
        $order->update([
            'payment_status' => 'canceled',
        ]);

        Log::info('Payment canceled', [
            'payment_id' => $payment->id,
            'order_id' => $order->id,
        ]);
    }

    public function createRefund(Payment $payment, ?float $amount = null): void
    {
        try {
            $refundData = [
                'payment_intent' => $payment->provider_reference,
            ];

            if ($amount !== null) {
                $refundData['amount'] = (int) ($amount * 100);
            }

            $refund = $this->client()->refunds->create($refundData);

            $refundedAmount = ($refund['amount'] ?? 0) / 100;

            $payment->update([
                'status' => 'refunded',
                'refunded_amount' => $refundedAmount,
                'refunded_at' => now(),
                'metadata' => array_merge($payment->metadata ?? [], [
                    'stripe_refund_id' => $refund['id'] ?? null,
                ]),
            ]);

            $order = $payment->order;
            $order->increment('refunded_total', $refundedAmount);

            Log::info('Refund created', [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'refunded_amount' => $refundedAmount,
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Failed to create refund', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}

