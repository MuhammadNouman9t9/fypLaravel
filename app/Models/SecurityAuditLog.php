<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SecurityAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'event_type',
        'event_category',
        'ip_address',
        'user_agent',
        'action',
        'status',
        'description',
        'metadata',
        'request_data',
        'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'request_data' => 'array',
            'logged_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $log): void {
            if (blank($log->uuid)) {
                $log->uuid = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
