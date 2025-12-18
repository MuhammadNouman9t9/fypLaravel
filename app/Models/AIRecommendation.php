<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AIRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'session_id',
        'context_type',
        'algorithm',
        'input_payload',
        'products',
        'confidence_score',
        'metadata',
        'requested_at',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'input_payload' => 'array',
            'products' => 'array',
            'confidence_score' => 'decimal:2',
            'metadata' => 'array',
            'requested_at' => 'datetime',
            'responded_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $recommendation): void {
            if (blank($recommendation->uuid)) {
                $recommendation->uuid = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
