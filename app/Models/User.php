<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $appends = [
        'name',
    ];

    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'preferred_language',
        'timezone',
        'avatar_path',
        'marketing_opt_in',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'marketing_opt_in' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $user): void {
            if (blank($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });
    }

    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function otps(): HasMany
    {
        return $this->hasMany(Otp::class);
    }

    public function sendOtp(string $channel = 'phone'): string
    {
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $this->otps()->create([
            'otp' => $otp,
            'phone' => $this->phone,
            'expires_at' => now()->addMinutes(10),
        ]);

        $notification = new \App\Notifications\SendOtpNotification($otp, $channel);
        $this->notify($notification);

        // Send SMS if channel is phone
        if ($channel === 'phone' && $this->phone) {
            $smsService = app(\App\Services\SmsService::class);
            $message = "Your OTP is: {$otp}. This OTP will expire in 10 minutes.";
            $smsService->send($this->phone, $message);
        }

        return $otp;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)
            ->withPivot(['assigned_by', 'assigned_at'])
            ->withTimestamps();
    }

    public function hasRole(string $roleName): bool
    {
        // Check if roles are already loaded
        if ($this->relationLoaded('roles')) {
            return $this->roles->contains('name', $roleName);
        }

        // Otherwise query the database
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isOwner(): bool
    {
        return $this->hasRole('owner');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isSecurityConsultant(): bool
    {
        return $this->hasRole('security_consultant');
    }

    public function hasTwoFactorEnabled(): bool
    {
        return ! is_null($this->two_factor_confirmed_at);
    }

    public function getTwoFactorSecret(): ?string
    {
        return $this->two_factor_secret ? decrypt($this->two_factor_secret) : null;
    }

    public function getRecoveryCodes(): array
    {
        if (! $this->two_factor_recovery_codes) {
            return [];
        }

        return json_decode(decrypt($this->two_factor_recovery_codes), true) ?? [];
    }
}
