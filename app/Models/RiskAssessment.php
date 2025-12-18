<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RiskAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'session_id',
        'property_type',
        'property_size',
        'occupancy_pattern',
        'neighborhood_profile',
        'score',
        'risk_level',
        'recommendations',
        'analysis',
        'input_payload',
        'metadata',
        'analyzed_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'recommendations' => 'array',
            'analysis' => 'array',
            'input_payload' => 'array',
            'metadata' => 'array',
            'analyzed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $assessment): void {
            if (blank($assessment->uuid)) {
                $assessment->uuid = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
