<?php

namespace App\Services;

use App\Models\FraudAlert;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;

class FraudDetectionService
{
    protected AuditLogService $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    public function analyzePayment(Payment $payment, Order $order): ?FraudAlert
    {
        if (! config('safenest.ai.fraud_detection.enabled')) {
            return null;
        }

        // Check if Python API is configured
        $pythonApiUrl = config('services.safenest.python_api.url');
        if ($pythonApiUrl) {
            return $this->analyzePaymentWithPython($payment, $order);
        }

        // Use PHP-based implementation
        return $this->analyzePaymentWithPHP($payment, $order);
    }

    protected function analyzePaymentWithPython(Payment $payment, Order $order): ?FraudAlert
    {
        $apiUrl = config('services.safenest.python_api.url');
        $apiKey = config('services.safenest.python_api.key');

        if (! $apiUrl || ! $apiKey) {
            \Illuminate\Support\Facades\Log::error('Python API URL or key not configured');

            throw new \Exception('Python API not configured. Please set SAFENEST_PYTHON_API_URL and SAFENEST_PYTHON_API_KEY in .env');
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout(10)->post("{$apiUrl}/fraud-detection/payment", [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'amount' => $payment->amount,
                'user_id' => $order->user_id,
                'payment_method' => $payment->method,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (! isset($data['risk_score'])) {
                    throw new \Exception('Invalid response from Python API: missing risk_score');
                }

                if ($data['risk_score'] >= 30) {
                    $alert = FraudAlert::create([
                        'order_id' => $order->id,
                        'payment_id' => $payment->id,
                        'user_id' => $order->user_id,
                        'score' => $data['risk_score'],
                        'risk_level' => $data['risk_level'] ?? 'moderate',
                        'status' => 'open',
                        'reason' => $data['reason'] ?? 'Fraud detected by AI',
                        'flags' => $data['flags'] ?? [],
                        'metadata' => $data['metadata'] ?? [],
                        'detected_at' => now(),
                    ]);

                    $this->auditLogService->logFraudDetection(
                        'Payment fraud detected (Python AI)',
                        "Fraud alert created for order {$order->order_number}",
                        ['alert_id' => $alert->id, 'risk_score' => $data['risk_score']]
                    );

                    return $alert;
                }

                return null;
            }

            throw new \Exception('Python API returned error: '.$response->body());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Python fraud detection API error: '.$e->getMessage());

            throw $e;
        }
    }

    protected function analyzePaymentWithPHP(Payment $payment, Order $order): ?FraudAlert
    {
        $riskScore = 0;
        $flags = [];
        $reasons = [];

        // Check payment amount anomalies
        $amountRisk = $this->checkAmountAnomaly($payment->amount, $order->user_id);
        if ($amountRisk > 0) {
            $riskScore += $amountRisk;
            $flags[] = 'unusual_amount';
            $reasons[] = 'Payment amount is unusually high compared to user history';
        }

        // Check velocity (multiple orders in short time)
        $velocityRisk = $this->checkVelocity($order->user_id);
        if ($velocityRisk > 0) {
            $riskScore += $velocityRisk;
            $flags[] = 'high_velocity';
            $reasons[] = 'Multiple orders placed in short time period';
        }

        // Check IP address anomalies
        $ipRisk = $this->checkIPAnomaly($order->user_id);
        if ($ipRisk > 0) {
            $riskScore += $ipRisk;
            $flags[] = 'suspicious_ip';
            $reasons[] = 'Order placed from unusual location or IP';
        }

        // Check payment method
        $methodRisk = $this->checkPaymentMethod($payment);
        if ($methodRisk > 0) {
            $riskScore += $methodRisk;
            $flags[] = 'risky_payment_method';
            $reasons[] = 'Payment method has higher fraud risk';
        }

        // Check user account age
        $accountRisk = $this->checkAccountAge($order->user_id);
        if ($accountRisk > 0) {
            $riskScore += $accountRisk;
            $flags[] = 'new_account';
            $reasons[] = 'New account with high-value order';
        }

        // Check billing/shipping mismatch
        $addressRisk = $this->checkAddressMismatch($order);
        if ($addressRisk > 0) {
            $riskScore += $addressRisk;
            $flags[] = 'address_mismatch';
            $reasons[] = 'Billing and shipping addresses differ significantly';
        }

        // Determine risk level
        $riskLevel = $this->getRiskLevel($riskScore);

        // Only create alert if risk is moderate or higher
        if ($riskScore >= 30) {
            $alert = FraudAlert::create([
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'user_id' => $order->user_id,
                'score' => $riskScore,
                'risk_level' => $riskLevel,
                'status' => 'open',
                'reason' => implode('; ', $reasons),
                'flags' => $flags,
                'metadata' => [
                    'amount' => $payment->amount,
                    'payment_method' => $payment->method,
                    'order_number' => $order->order_number,
                ],
                'detected_at' => now(),
            ]);

            // Log fraud detection
            $this->auditLogService->logFraudDetection(
                'Payment fraud detected',
                "Fraud alert created for order {$order->order_number} with risk score {$riskScore}",
                ['alert_id' => $alert->id, 'risk_score' => $riskScore, 'flags' => $flags]
            );

            return $alert;
        }

        return null;
    }

    public function analyzeLoginAttempt(User $user, bool $success, string $ipAddress, ?string $userAgent = null): ?FraudAlert
    {
        if (! config('safenest.ai.fraud_detection.enabled')) {
            return null;
        }

        $riskScore = 0;
        $flags = [];
        $reasons = [];

        // Check for multiple failed login attempts
        $failedAttempts = $this->getRecentFailedLogins($user->email, $ipAddress);
        if ($failedAttempts > 3) {
            $riskScore += 40;
            $flags[] = 'multiple_failed_attempts';
            $reasons[] = "{$failedAttempts} failed login attempts detected";
        }

        // Check IP address change
        if ($user->last_login_at) {
            $lastIP = $this->getLastLoginIP($user->id);
            if ($lastIP && $lastIP !== $ipAddress) {
                // Check if IP is from different country (simplified check)
                $riskScore += 20;
                $flags[] = 'ip_location_change';
                $reasons[] = 'Login from different IP address';
            }
        }

        // Check unusual time
        $timeRisk = $this->checkUnusualLoginTime($user->id);
        if ($timeRisk > 0) {
            $riskScore += $timeRisk;
            $flags[] = 'unusual_login_time';
            $reasons[] = 'Login at unusual time';
        }

        if ($riskScore >= 30) {
            $alert = FraudAlert::create([
                'user_id' => $user->id,
                'score' => $riskScore,
                'risk_level' => $this->getRiskLevel($riskScore),
                'status' => 'open',
                'reason' => implode('; ', $reasons),
                'flags' => $flags,
                'metadata' => [
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'failed_attempts' => $failedAttempts,
                ],
                'detected_at' => now(),
            ]);

            $this->auditLogService->logSuspiciousActivity(
                'Suspicious login activity',
                "Fraud alert created for user {$user->email} with risk score {$riskScore}",
                ['alert_id' => $alert->id, 'risk_score' => $riskScore]
            );

            return $alert;
        }

        return null;
    }

    protected function checkAmountAnomaly(float $amount, ?int $userId): int
    {
        if (! $userId) {
            return 0;
        }

        $userAverage = Order::where('user_id', $userId)
            ->where('payment_status', 'paid')
            ->avg('grand_total');

        if (! $userAverage) {
            // New user, check if amount is very high
            return $amount > 1000 ? 30 : 0;
        }

        // If amount is 3x higher than average, flag it
        if ($amount > ($userAverage * 3)) {
            return 40;
        }

        // If amount is 2x higher, moderate risk
        if ($amount > ($userAverage * 2)) {
            return 20;
        }

        return 0;
    }

    protected function checkVelocity(?int $userId): int
    {
        if (! $userId) {
            return 0;
        }

        $recentOrders = Order::where('user_id', $userId)
            ->where('created_at', '>=', now()->subHours(1))
            ->count();

        if ($recentOrders > 5) {
            return 50; // Very high velocity
        } elseif ($recentOrders > 3) {
            return 30; // High velocity
        } elseif ($recentOrders > 1) {
            return 10; // Moderate velocity
        }

        return 0;
    }

    protected function checkIPAnomaly(?int $userId): int
    {
        if (! $userId) {
            return 10; // Guest checkout has some risk
        }

        // In a real implementation, you'd check IP geolocation
        // For now, we'll return 0 as this requires external service
        return 0;
    }

    protected function checkPaymentMethod(Payment $payment): int
    {
        // Some payment methods have higher fraud risk
        $riskyMethods = ['prepaid_card', 'gift_card', 'cryptocurrency'];

        if (in_array($payment->method, $riskyMethods)) {
            return 25;
        }

        return 0;
    }

    protected function checkAccountAge(?int $userId): int
    {
        if (! $userId) {
            return 20; // Guest checkout
        }

        $user = User::find($userId);
        if (! $user) {
            return 20;
        }

        $accountAge = $user->created_at->diffInDays(now());

        if ($accountAge < 1) {
            return 30; // Brand new account
        } elseif ($accountAge < 7) {
            return 15; // Very new account
        } elseif ($accountAge < 30) {
            return 5; // New account
        }

        return 0;
    }

    protected function checkAddressMismatch(Order $order): int
    {
        if (! $order->shipping_address_id || ! $order->billing_address_id) {
            return 0;
        }

        $shipping = $order->shipping_snapshot;
        $billing = $order->billing_snapshot;

        if (! $shipping || ! $billing) {
            return 0;
        }

        // Check if cities are different
        if (($shipping['city'] ?? '') !== ($billing['city'] ?? '')) {
            return 15;
        }

        // Check if countries are different
        if (($shipping['country'] ?? '') !== ($billing['country'] ?? '')) {
            return 30;
        }

        return 0;
    }

    protected function getRecentFailedLogins(string $email, string $ipAddress): int
    {
        // This would check security audit logs for failed login attempts
        // For now, return 0 as this requires audit log integration
        return 0;
    }

    protected function getLastLoginIP(int $userId): ?string
    {
        // This would check security audit logs for last successful login IP
        // For now, return null
        return null;
    }

    protected function checkUnusualLoginTime(int $userId): int
    {
        $user = User::find($userId);
        if (! $user || ! $user->last_login_at) {
            return 0;
        }

        $lastLoginHour = $user->last_login_at->hour;
        $currentHour = now()->hour;

        // If login is more than 8 hours different from usual time
        $diff = abs($currentHour - $lastLoginHour);
        if ($diff > 8 && $diff < 16) {
            return 10;
        }

        return 0;
    }

    protected function getRiskLevel(float $score): string
    {
        if ($score >= 70) {
            return 'critical';
        } elseif ($score >= 50) {
            return 'high';
        } elseif ($score >= 30) {
            return 'moderate';
        } else {
            return 'low';
        }
    }

    public function resolveAlert(FraudAlert $alert, int $resolvedBy, string $resolution): void
    {
        $alert->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => $resolvedBy,
            'metadata' => array_merge($alert->metadata ?? [], [
                'resolution' => $resolution,
                'resolved_at' => now()->toIso8601String(),
            ]),
        ]);
    }
}
