<?php

namespace TheBachtiarz\Base\Traits\Enums;

use Illuminate\Support\Str;
use UnitEnum;

/**
 * Abstract Enum Trait
 */
trait AbstractEnum
{
    /**
     * Get enum(s) case and label
     */
    public static function all(): array
    {
        return array_merge(
            ...array_map(
                callback: fn(UnitEnum|self $case): array => [$case->value => $case->getLabel()],
                array: static::cases(),
            ),
        );
    }

    /**
     * Get value only from enum
     *
     * @return UnitEnum[]
     */
    public static function values(): array
    {
        return array_column(
            array: static::cases(),
            column_key: 'value',
        );
    }

    /**
     * Get enum(s) as messages
     */
    public static function messages(): array
    {
        return array_map(
            callback: fn(UnitEnum|self $case): string => sprintf('%s => %s', $case->value, $case->getLabel()),
            array: static::cases(),
        );
    }

    /**
     * Get label
     */
    public function getLabel(): string
    {
        return Str::headline((string) $this->value);
    }
}
