<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\ProcessPaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Services\FraudDetectionService;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function __construct(
        private StripeService $stripeService,
        private FraudDetectionService $fraudDetectionService
    ) {}

    public function checkout(Order $order): View|RedirectResponse
    {
        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('orders.show', $order)
                ->with('status', __('This order has already been paid.'));
        }

        if ($order->grand_total <= 0) {
            return redirect()
                ->route('orders.show', $order)
                ->with('status', __('This order has no amount to pay.'));
        }

        $paymentIntent = $this->stripeService->createPaymentIntent($order);

        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $order->grand_total,
            'currency' => $order->currency,
            'provider' => 'stripe',
            'provider_reference' => $paymentIntent->id,
            'status' => 'pending',
            'metadata' => [
                'client_secret' => $paymentIntent->client_secret,
            ],
        ]);

        return view('payment.checkout', [
            'order' => $order,
            'payment' => $payment,
            'paymentIntent' => $paymentIntent,
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    public function process(ProcessPaymentRequest $request): JsonResponse|RedirectResponse
    {
        $order = Order::findOrFail($request->validated('order_id'));
        $paymentIntentId = $request->validated('payment_intent_id');

        $payment = Payment::where('order_id', $order->id)
            ->where('provider_reference', $paymentIntentId)
            ->firstOrFail();

        try {
            $paymentIntent = $this->stripeService->retrievePaymentIntent($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                $payment->update([
                    'status' => 'succeeded',
                    'method' => $paymentIntent->payment_method_types[0] ?? null,
                    'captured_at' => now(),
                ]);

                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]);

                // Run fraud detection after successful payment
                try {
                    $fraudAlert = $this->fraudDetectionService->analyzePayment($payment, $order);
                    if ($fraudAlert && $fraudAlert->isHighRisk()) {
                        // Log high-risk fraud detection
                        Log::warning('High-risk fraud detected for payment', [
                            'payment_id' => $payment->id,
                            'order_id' => $order->id,
                            'fraud_alert_id' => $fraudAlert->id,
                            'risk_score' => $fraudAlert->score,
                        ]);
                    }
                } catch (\Exception $e) {
                    // Don't fail payment if fraud detection fails
                    Log::error('Fraud detection error', [
                        'payment_id' => $payment->id,
                        'error' => $e->getMessage(),
                    ]);
                }

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => __('Payment successful.'),
                        'redirect' => route('orders.show', $order),
                    ]);
                }

                return redirect()
                    ->route('orders.show', $order)
                    ->with('status', __('Payment successful.'));
            }

            if ($paymentIntent->status === 'requires_payment_method') {
                $errorMessage = $paymentIntent->last_payment_error->message ?? __('Payment failed. Please try again.');

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'error' => $paymentIntent->last_payment_error->code ?? null,
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return back()
                    ->withErrors(['payment' => $errorMessage])
                    ->withInput();
            }

            // Handle other payment intent statuses
            if (in_array($paymentIntent->status, ['processing', 'requires_action', 'requires_confirmation'])) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => __('Payment is still processing. Please wait...'),
                        'status' => $paymentIntent->status,
                    ], Response::HTTP_ACCEPTED);
                }

                return back()
                    ->with('status', __('Payment is still processing. Please wait...'));
            }

            // Handle failed or canceled statuses
            if (in_array($paymentIntent->status, ['canceled', 'failed'])) {
                $errorMessage = $paymentIntent->last_payment_error->message ?? __('Payment was not completed.');

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return back()
                    ->withErrors(['payment' => $errorMessage])
                    ->withInput();
            }
        } catch (ApiErrorException $e) {
            Log::error('Stripe API error during payment processing', [
                'order_id' => $order->id,
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
                'stripe_error' => $e->getStripeCode(),
            ]);

            $errorMessage = __('An error occurred while processing your payment.');

            // Provide more specific error messages for common Stripe errors
            if ($e->getStripeCode() === 'payment_intent_unexpected_state') {
                $errorMessage = __('The payment intent is in an unexpected state. Please try again.');
            } elseif (str_contains($e->getMessage(), 'No such payment_intent')) {
                $errorMessage = __('Payment intent not found. Please try again.');
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return back()
                ->withErrors(['payment' => $errorMessage])
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Payment processing error', [
                'order_id' => $order->id,
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('An error occurred while processing your payment.'),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return back()
                ->withErrors(['payment' => __('An error occurred while processing your payment.')])
                ->withInput();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => __('Payment is still processing.'),
            ], Response::HTTP_ACCEPTED);
        }

        return back()
            ->with('status', __('Payment is still processing.'));
    }

    public function webhook(Request $request): Response
    {
        $payload = $request->all();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            \Stripe\Webhook::constructEvent(
                $request->getContent(),
                $sigHeader,
                $webhookSecret
            );
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Invalid signature'], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Webhook error'], Response::HTTP_BAD_REQUEST);
        }

        $this->stripeService->handleWebhook($payload);

        return response()->json(['received' => true]);
    }

    public function success(Order $order): View|RedirectResponse
    {
        if ($order->payment_status !== 'paid') {
            return redirect()
                ->route('payment.checkout', $order)
                ->with('status', __('Please complete your payment.'));
        }

        return view('payment.success', [
            'order' => $order,
        ]);
    }

    public function cancel(Order $order): RedirectResponse
    {
        return redirect()
            ->route('payment.checkout', $order)
            ->with('status', __('Payment was canceled. You can try again.'));
    }
}
