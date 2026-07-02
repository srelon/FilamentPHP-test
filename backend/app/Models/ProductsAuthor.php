<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsAuthor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'avatar_color',
        'photo',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'photo' => 'array',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'author_id');
    }

    protected static function booted(): void
    {
        static::saved(fn () => CacheService::flushOnProductWrite());
        static::deleted(fn () => CacheService::flushOnProductWrite());
    }
}
