<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'order_id',
        'tracking_number',
        'carrier',
        'service_level',
        'status',
        'packages',
        'shipping_address_snapshot',
        'label_url',
        'shipped_at',
        'delivered_at',
        'expected_delivery_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'packages' => 'array',
            'shipping_address_snapshot' => 'array',
            'metadata' => 'array',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'expected_delivery_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $shipment): void {
            if (blank($shipment->uuid)) {
                $shipment->uuid = (string) Str::uuid();
            }

            if (blank($shipment->tracking_number)) {
                $shipment->tracking_number = 'TRK-'.strtoupper(Str::random(12));
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function generateTrackingNumber(): string
    {
        return 'TRK-'.strtoupper(Str::random(12));
    }
}
