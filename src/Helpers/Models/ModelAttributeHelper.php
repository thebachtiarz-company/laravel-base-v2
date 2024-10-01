<?php

namespace TheBachtiarz\Base\Helpers\Models;

use Illuminate\Database\Eloquent\Model;

class ModelAttributeHelper
{
    /**
     * Assert an entity should be a model instance
     *
     * @param Model|array $entity Value could be an array or Model it self
     * @param class-string<Model>|null $instance A class Model instance should be
     * @return Model
     */
    public static function entityShouldBe(Model|array $entity, ?string $instance = null): Model
    {
        if ($entity instanceof Model) {
            return $entity;
        }

        if (!$instance) {
            return null;
        }

        $model = new $instance();
        assert($model instanceof Model);

        return $model->setRawAttributes($entity)->withoutRelations();
    }
}
