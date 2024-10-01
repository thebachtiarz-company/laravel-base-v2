<?php

namespace TheBachtiarz\Base\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\Base\DTOs\Models\ModelComponentDTO;
use TheBachtiarz\Base\Helpers\Models\ModelAttributeHelper;

class ModelAttributeCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Model
    {
        if (!$value) {
            return null;
        }

        $value = new ModelComponentDTO(...json_decode($value, true));

        return ModelAttributeHelper::entityShouldBe(
            entity: $value->attributes,
            instance: $value->className,
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (!$value) {
            return null;
        }

        assert($value instanceof Model);

        return json_encode((new ModelComponentDTO(
            className: $value::class,
            attributes: $value->withoutRelations()->toArray(),
        ))->toArray());
    }
}
