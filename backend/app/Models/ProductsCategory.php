<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'image',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'icon' => 'array',
            'image' => 'array',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seo', 'type', 'record_id');
    }

    protected static function booted(): void
    {
        static::saved(fn () => CacheService::flushOnCategoryWrite());
        static::deleted(fn () => CacheService::flushOnCategoryWrite());
    }
}
