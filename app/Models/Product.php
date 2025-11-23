<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'uuid',
        'sku',
        'slug',
        'name',
        'brand',
        'summary',
        'description',
        'price',
        'compare_at_price',
        'currency',
        'rating_average',
        'reviews_count',
        'availability_status',
        'is_active',
        'is_featured',
        'warranty_period',
        'return_policy',
        'specifications_snapshot',
        'meta',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'specifications_snapshot' => 'array',
        'meta' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $product): void {
            if (blank($product->uuid)) {
                $product->uuid = (string) Str::uuid();
            }

            if (blank($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
            ->withPivot(['is_primary', 'assigned_at'])
            ->withTimestamps();
    }

    public function primaryCategory(): BelongsToMany
    {
        return $this->categories()->wherePivot('is_primary', true);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProductMedia::class)->orderBy('position');
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('display_order');
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(InventoryItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $categoryIds = array_filter((array) data_get($filters, 'category_ids', []));

        return $query
            ->when(! empty($categoryIds), function (Builder $builder) use ($categoryIds): void {
                $builder->whereHas('categories', function (Builder $relation) use ($categoryIds): void {
                    $relation->whereIn('categories.id', $categoryIds);
                });
            })
            ->when(data_get($filters, 'search'), function (Builder $builder, string $term): void {
                $builder->where(function (Builder $subQuery) use ($term): void {
                    $subQuery
                        ->where('name', 'like', "%{$term}%")
                        ->orWhere('summary', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhere('brand', 'like', "%{$term}%");
                });
            })
            ->when(data_get($filters, 'brand'), function (Builder $builder, string $brand): void {
                $builder->where('brand', $brand);
            })
            ->when(empty($categoryIds) ? data_get($filters, 'category') : null, function (Builder $builder, $category): void {
                $builder->whereHas('categories', function (Builder $relation) use ($category): void {
                    $relation->where(function (Builder $categoryQuery) use ($category): void {
                        if (is_numeric($category)) {
                            $categoryQuery->where('categories.id', $category);
                        } else {
                            $categoryQuery->where('categories.slug', $category);
                        }
                    });
                });
            })
            ->when(data_get($filters, 'is_featured'), function (Builder $builder): void {
                $builder->where('is_featured', true);
            })
            ->when(data_get($filters, 'min_price'), function (Builder $builder, $value): void {
                $builder->where('price', '>=', $value);
            })
            ->when(data_get($filters, 'max_price'), function (Builder $builder, $value): void {
                $builder->where('price', '<=', $value);
            });
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        $media = $this->media->firstWhere('is_primary', true) ?? $this->media->first();

        return $media?->file_path;
    }

    public function getPrimaryCategoryNameAttribute(): ?string
    {
        $category = $this->categories->firstWhere(fn ($cat) => (bool) $cat->pivot?->is_primary) ?? $this->categories->first();

        return $category?->name;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
