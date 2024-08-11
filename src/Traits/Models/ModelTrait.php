<?php

namespace TheBachtiarz\Base\Traits\Models;

use TheBachtiarz\Base\Helpers\General\ModelHelper;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Model Trait.
 *
 * Make sure the Model is implements with "\TheBachtiarz\Base\Interfaces\Models\ModelInterface" interface.
 * Or just simply use the extends from "\TheBachtiarz\Base\Models\AbstractModel" abstract model.
 */
trait ModelTrait
{
    /**
     * Define factory for model
     *
     * @var class-string<Factory>
     */
    protected $modelFactory = null;

    // ? Public Methods

    /**
     * Get data
     */
    public function getData(string $attribute): mixed
    {
        return $this->__get(key: $attribute);
    }

    /**
     * Set data
     */
    public function setData(string $attribute, mixed $value): static
    {
        $this->__set(key: $attribute, value: $value);

        return $this;
    }

    /**
     * Get primary key attribute name
     */
    public function getPrimaryKeyAttribute(): string
    {
        return $this->primaryKey;
    }

    // ? Protected Methods

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory()
    {
        return (new static())->modelFactory::new();
    }

    // ? Private Methods

    // ? Getter Modules

    /**
     * Get id
     */
    public function getId(): int|string|null
    {
        return $this->__get(key: $this->primaryKey);
    }

    /**
     * Get created at
     */
    public function getCreatedAt(): Carbon
    {
        return $this->__get(key: ModelInterface::ATTRIBUTE_CREATED_AT);
    }

    /**
     * Get updated at
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->__get(key: ModelInterface::ATTRIBUTE_UPDATED_AT);
    }

    // ? Setter Modules

    /**
     * Set id
     */
    public function setId(int|string $primaryKey): static
    {
        $this->__set(key: $this->primaryKey, value: $primaryKey);

        return $this;
    }

    // ? Map Modules

    /**
     * Get entity simple map
     *
     * @param string[] $attributes Show listed attribute(s)
     * @param string[] $hides Hide listed attribute(s)
     */
    public function simpleListMap(array $attributes = [], array $hides = []): array
    {
        $this->setVisible(
            visible: array_merge(
                ModelHelper::getTableColumnsFromModel(new static()),
                $attributes,
            ),
        );

        $this->makeHidden(
            attributes: array_unique(
                array: array_merge(
                    [
                        $this->primaryKey,
                        ModelInterface::ATTRIBUTE_CREATED_AT,
                        ModelInterface::ATTRIBUTE_UPDATED_AT,
                    ],
                    $hides,
                ),
            ),
        );

        return $this->toArray();
    }

    // ? Scope Modules

    /**
     * Get entity by attribute
     */
    public function scopeGetByAttribute(
        EloquentBuilder|QueryBuilder $builder,
        string $column,
        mixed $value,
        string $operator = '=',
    ): EloquentBuilder|QueryBuilder {
        return $builder->where(
            column: DB::raw("BINARY `$column`"),
            operator: $operator,
            value: $value,
        );
    }
}
