<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStock extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        'before_price',
        'real_price',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'before_price' => 'decimal:2',
            'real_price' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function booted(): void
    {
        static::saved(fn () => CacheService::flushOnProductWrite());
        static::deleted(fn () => CacheService::flushOnProductWrite());
    }
}
