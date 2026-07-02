<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    const TTL_LAYOUT = 900;
    const TTL_HOME = 600;

    const TAG_LAYOUT = 'layout';
    const TAG_HOME = 'home';

    public static function flushOnMenuWrite(): void
    {
        Cache::tags([self::TAG_LAYOUT])->flush();
    }

    public static function flushOnContactWrite(): void
    {
        Cache::tags([self::TAG_LAYOUT])->flush();
    }

    public static function flushOnCategoryWrite(): void
    {
        Cache::tags([self::TAG_LAYOUT, self::TAG_HOME])->flush();
    }

    public static function flushOnProductWrite(): void
    {
        Cache::tags([self::TAG_HOME])->flush();
    }

    public static function flushOnNewsWrite(): void
    {
        Cache::tags([self::TAG_HOME])->flush();
    }
}
