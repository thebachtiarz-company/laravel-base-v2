<?php

namespace TheBachtiarz\Base\Helpers\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ResponseCollectionHelper
{
    /**
     * Responses collection
     */
    private static Collection $responses;

    /**
     * Template string of unique code
     */
    private static string $uniqueCodeTemplate = '__%s_%s';

    /**
     * Current response unique code
     */
    private static string $currentUniqueCode = '';

    // ? Public Methods

    /**
     * Get response collection
     */
    public static function getResponses(): Collection
    {
        return static::instance();
    }

    /**
     * Get latest data from response collection
     */
    public static function getLatestResponse(): mixed
    {
        return static::getResponses()->last();
    }

    /**
     * Add new response
     *
     * @template T
     *
     * @param T $data Response Data
     * @param ?string $key Custom Attribute
     * @return static
     */
    public static function add(mixed $data, ?string $key = null): static
    {
        static::instance()->put(
            key: sprintf(
                static::getUniqueCodeTemplate(),
                $key ?? 'process',
                static::setCurrentUniqueCode()->getCurrentUniqueCode(),
            ),
            value: $data,
        );

        return new static();
    }

    /**
     * Delete temporary data by key
     */
    public static function forget(string $key): bool
    {
        static::instance()->forget($key);

        return true;
    }

    /**
     * Reset temporary data
     */
    public static function flush(): bool
    {
        static::$responses = new Collection();

        return true;
    }

    // ? Protected Methods

    /**
     * Get instance response collection
     */
    protected static function instance(): Collection
    {
        try {
            static::$responses->count();
        } catch (\Throwable $th) {
            static::$responses = new Collection();
        }

        return static::$responses;
    }

    // ? Private Methods

    // ? Getter Modules

    /**
     * Get unique code template
     */
    public static function getUniqueCodeTemplate(): string
    {
        return static::$uniqueCodeTemplate;
    }

    /**
     * Get current unique code
     */
    public static function getCurrentUniqueCode(): string
    {
        return static::$currentUniqueCode;
    }

    // ? Setter Modules

    /**
     * Set unique code template
     */
    public static function setUniqueCodeTemplate(string $uniqueCodeTemplate): static
    {
        static::$uniqueCodeTemplate = $uniqueCodeTemplate;

        return new static();
    }

    /**
     * Set current unique code
     */
    public static function setCurrentUniqueCode(?string $uniqueCode = null): static
    {
        static::$currentUniqueCode = $uniqueCode ?? Str::uuid()->toString();

        return new static();
    }
}
