<?php

namespace TheBachtiarz\Base\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class UnitEnumCast implements CastsAttributes
{
    /**
     * Create a new cast class instance.
     *
     * @param class-string<UnitEnum> $unitEnum
     */
    public function __construct(
        protected string $unitEnum,
    ) {}

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $enum = $this->unitEnum;
        assert($enum instanceof UnitEnum);

        return $enum::tryFrom($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        assert($value instanceof UnitEnum || $value === null);

        return $value?->value;
    }
}
