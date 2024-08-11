<?php

namespace TheBachtiarz\Base\Helpers\General;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TemporaryHelper
{
    /**
     * Temporary Data
     */
    protected static Collection $temporaryData;

    // ? Public Methods

    /**
     * Get data
     */
    public static function get(string|null $attribute = null): mixed
    {
        static::init();

        return @$attribute ? static::$temporaryData->get($attribute) : static::$temporaryData->toArray();
    }

    /**
     * Set temporaries data
     *
     * @param array $temporariesData
     *
     * @return static
     */
    public static function set(array $temporariesData = []): static
    {
        static::init();

        static::$temporaryData = collect($temporariesData);

        return new static();
    }

    /**
     * Add data into temporary
     *
     * @return static
     */
    public static function push(string $attribute, mixed $value): static
    {
        static::init();

        static::$temporaryData->put(key: $attribute, value: $value);

        return new static();
    }

    /**
     * Save temporary data into cache
     *
     * @return static
     */
    public static function cache(): static
    {
        static::init();

        if (static::$temporaryData->count()) {
            CacheHelper::setCache(
                cacheName: sprintf(
                    '%s-%s',
                    Carbon::now()->getTimestampMs(),
                    StringHelper::shuffleBoth(7),
                ),
                value: static::$temporaryData,
            );
        }

        return new static();
    }

    /**
     * Delete temporary data by key
     *
     * @return static
     */
    public static function forget(string $key): static
    {
        static::init();

        static::$temporaryData->forget($key);
        CacheHelper::forget($key);

        return new static();
    }

    /**
     * Reset temporary data
     *
     * @return static
     */
    public static function flush(): static
    {
        static::init();

        static::$temporaryData = collect();

        return new static();
    }

    // ? Protected Methods

    /**
     * Init temporary collection
     */
    protected static function init(): void
    {
        try {
            static::$temporaryData->count();
        } catch (\Throwable) {
            static::$temporaryData = collect();
        }
    }
}
