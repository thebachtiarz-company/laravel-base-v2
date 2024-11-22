<?php

namespace TheBachtiarz\Base\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class DataTransferObjectCast implements CastsAttributes
{
    /**
     * Create a new cast class instance.
     *
     * @param class-string<\TheBachtiarz\Base\DTOs\AbstractDTO> $dtoClass
     */
    public function __construct(
        protected string $dtoClass,
    ) {}

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $dto = app($this->dtoClass);
        assert($dto instanceof \TheBachtiarz\Base\DTOs\AbstractDTO);

        return $dto->fromArray($value ?? []);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        assert($value instanceof \TheBachtiarz\Base\DTOs\AbstractDTO || $value === null);

        return $value?->toArray();
    }
}
