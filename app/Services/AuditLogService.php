<?php

namespace App\Services;

use App\Models\SecurityAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function log(string $eventType, string $action, string $status = 'success', ?string $description = null, ?array $metadata = null): SecurityAuditLog
    {
        $request = request();

        return SecurityAuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => $eventType,
            'event_category' => $this->getEventCategory($eventType),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => $action,
            'status' => $status,
            'description' => $description,
            'metadata' => $metadata,
            'request_data' => $this->sanitizeRequestData($request),
            'logged_at' => now(),
        ]);
    }

    public function logSecurityEvent(string $action, string $status = 'success', ?string $description = null, ?array $metadata = null): SecurityAuditLog
    {
        return $this->log('security_event', $action, $status, $description, $metadata);
    }

    public function logLoginAttempt(string $email, bool $success, ?string $reason = null): SecurityAuditLog
    {
        return $this->log(
            'login_attempt',
            'User login attempt',
            $success ? 'success' : 'failed',
            $success ? 'Successful login' : "Failed login: {$reason}",
            ['email' => $email, 'reason' => $reason]
        );
    }

    public function logSuspiciousActivity(string $action, string $description, array $metadata = []): SecurityAuditLog
    {
        return $this->log('suspicious_activity', $action, 'warning', $description, $metadata);
    }

    public function logFraudDetection(string $action, string $description, array $metadata = []): SecurityAuditLog
    {
        return $this->log('fraud_detection', $action, 'warning', $description, $metadata);
    }

    public function logAdminAction(string $action, ?string $description = null, ?array $metadata = null): SecurityAuditLog
    {
        return $this->log('admin_action', $action, 'success', $description, $metadata);
    }

    public function logPaymentActivity(string $action, string $status, ?string $description = null, ?array $metadata = null): SecurityAuditLog
    {
        return $this->log('payment_activity', $action, $status, $description, $metadata);
    }

    protected function getEventCategory(string $eventType): string
    {
        $categories = [
            'login_attempt' => 'authentication',
            'suspicious_activity' => 'security',
            'fraud_detection' => 'security',
            'admin_action' => 'administration',
            'payment_activity' => 'payment',
            'security_event' => 'security',
        ];

        return $categories[$eventType] ?? 'general';
    }

    protected function sanitizeRequestData(Request $request): array
    {
        $data = $request->all();

        $sensitive = [
            'password', 'password_confirmation', 'current_password',
            'card_number', 'cvv', 'cvc',
            'token', '_token', 'api_key',
            'secret', 'two_factor_secret',
            'code', 'otp', 'recovery_code', 'recovery_codes',
            'cnic', 'ssn',
            'client_secret', 'payment_intent_id',
            'stripe_secret', 'stripe_signature',
        ];
        foreach ($sensitive as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = '[REDACTED]';
            }
        }

        return $data;
    }

    public function getRecentLogs(int $limit = 50, ?string $eventType = null)
    {
        $query = SecurityAuditLog::query()
            ->with('user')
            ->orderByDesc('logged_at');

        if ($eventType) {
            $query->where('event_type', $eventType);
        }

        return $query->limit($limit)->get();
    }

    public function getSuspiciousActivities(int $days = 7)
    {
        return SecurityAuditLog::query()
            ->where('event_type', 'suspicious_activity')
            ->orWhere('event_type', 'fraud_detection')
            ->where('logged_at', '>=', now()->subDays($days))
            ->with('user')
            ->orderByDesc('logged_at')
            ->get();
    }
}
