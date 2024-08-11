<?php

namespace TheBachtiarz\Base\Interfaces\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

interface ModelInterface
{
    public const string ATTRIBUTE_CREATED_AT = 'created_at';
    public const string ATTRIBUTE_UPDATED_AT = 'updated_at';
    public const string ATTRIBUTE_DELETED_AT = 'deleted_at';

    // ? Public Methods

    /**
     * Get data
     */
    public function getData(string $attribute): mixed;

    /**
     * Set data
     */
    public function setData(string $attribute, mixed $value): static;

    /**
     * Get primary key attribute name
     */
    public function getPrimaryKeyAttribute(): string;

    // ? Protected Methods

    // ? Private Methods

    // ? Getter Modules

    /**
     * Get id
     */
    public function getId(): int|string|null;

    /**
     * Get created at
     */
    public function getCreatedAt(): Carbon;

    /**
     * Get updated at
     */
    public function getUpdatedAt(): Carbon;

    // ? Setter Modules

    /**
     * Set id
     */
    public function setId(int|string $primaryKey): static;

    // ? Map Modules

    /**
     * Get entity simple map
     *
     * @param string[] $attributes Show listed attribute(s)
     * @param string[] $hides Hide listed attribute(s)
     */
    public function simpleListMap(array $attributes = [], array $hides = []): array;

    // ? Scope Modules

    /**
     * Get entity by attribute
     */
    public function scopeGetByAttribute(EloquentBuilder|QueryBuilder $builder, string $column, mixed $value, string $operator = '='): EloquentBuilder|QueryBuilder;
}
