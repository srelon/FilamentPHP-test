<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Model;
use SolutionForest\FilamentTree\Concern\ModelTree;

class Menu extends Model
{
    use ModelTree;

    protected $fillable = [
        'name',
        'route',
        'parent_id',
        'type',
        'params',
        'sort_order',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'parent_id' => 'integer',
            'params' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => CacheService::flushOnMenuWrite());
        static::deleted(fn () => CacheService::flushOnMenuWrite());
    }
}
