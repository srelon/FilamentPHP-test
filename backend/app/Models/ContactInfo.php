<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactInfo extends Model
{
    use SoftDeletes;

    protected $table = 'contact_info';

    protected $fillable = [
        'name',
        'content',
        'icon',
        'key',
        'status',
        'sort_order',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => CacheService::flushOnContactWrite());
        static::deleted(fn () => CacheService::flushOnContactWrite());
    }
}
