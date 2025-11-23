<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SecurityLogService
{
    /**
     * Log security events
     */
    public function log(string $event, array $context = [], string $level = 'info'): void
    {
        $logData = [
            'event' => $event,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'context' => $context,
            'timestamp' => now()->toIso8601String(),
        ];

        // Log to Laravel log
        Log::channel('security')->{$level}($event, $logData);

        // Store in database for monitoring
        try {
            DB::table('security_logs')->insert([
                'event' => $event,
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'context' => json_encode($context),
                'level' => $level,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // If table doesn't exist, just log to file
            Log::warning('Security log table not found', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Log authentication attempts
     */
    public function logAuthAttempt(string $email, bool $success, ?string $reason = null): void
    {
        $this->log('auth.attempt', [
            'email' => $email,
            'success' => $success,
            'reason' => $reason,
        ], $success ? 'info' : 'warning');
    }

    /**
     * Log failed login attempts
     */
    public function logFailedLogin(string $email, string $reason = 'Invalid credentials'): void
    {
        $this->log('auth.failed', [
            'email' => $email,
            'reason' => $reason,
        ], 'warning');
    }

    /**
     * Log successful login
     */
    public function logSuccessfulLogin(int $userId): void
    {
        $this->log('auth.success', [
            'user_id' => $userId,
        ], 'info');
    }

    /**
     * Log password changes
     */
    public function logPasswordChange(int $userId): void
    {
        $this->log('auth.password_change', [
            'user_id' => $userId,
        ], 'info');
    }

    /**
     * Log suspicious activity
     */
    public function logSuspiciousActivity(string $activity, array $context = []): void
    {
        $this->log('security.suspicious', [
            'activity' => $activity,
            ...$context,
        ], 'warning');
    }

    /**
     * Log payment fraud attempts
     */
    public function logFraudAttempt(string $type, array $context = []): void
    {
        $this->log('fraud.attempt', [
            'type' => $type,
            ...$context,
        ], 'error');
    }

    /**
     * Get security logs for monitoring
     */
    public function getRecentLogs(int $limit = 100, ?string $level = null): array
    {
        try {
            $query = DB::table('security_logs')
                ->orderBy('created_at', 'desc')
                ->limit($limit);

            if ($level) {
                $query->where('level', $level);
            }

            return $query->get()->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get failed login attempts count
     */
    public function getFailedLoginCount(string $ipAddress, int $minutes = 15): int
    {
        try {
            return DB::table('security_logs')
                ->where('event', 'auth.failed')
                ->where('ip_address', $ipAddress)
                ->where('created_at', '>=', now()->subMinutes($minutes))
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
}



