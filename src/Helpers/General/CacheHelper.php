<?php

namespace TheBachtiarz\Base\Helpers\General;

use DateInterval;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;

class CacheHelper extends Cache
{
    /**
     * Check is cache available by key
     */
    public static function hasCache(string $cacheName): bool
    {
        return static::has($cacheName);
    }

    /**
     * Get cache by key
     */
    public static function getCache(string $cacheName): mixed
    {
        return static::get($cacheName);
    }

    /**
     * Set cache data forever
     */
    public static function setCache(string $cacheName, mixed $value): bool
    {
        return static::forever($cacheName, $value);
    }

    /**
     * Set cache data temporary with time to live
     *
     * @param DateTimeInterface|DateInterval|int $ttl default: 60 seconds
     */
    public static function setTemporaryCache(
        string $cacheName,
        mixed $value,
        DateTimeInterface|DateInterval|int $ttl = 60,
    ): bool {
        return static::put($cacheName, $value, $ttl);
    }

    /**
     * Delete a cache data by key
     */
    public static function deleteCache(string $cacheName): bool
    {
        return static::forget($cacheName);
    }

    /**
     * Erase/Remove all cache data
     */
    public static function eraseCaches(): bool
    {
        return static::flush();
    }
}
