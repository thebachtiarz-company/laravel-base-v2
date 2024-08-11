<?php

namespace TheBachtiarz\Base\Interfaces\Repositories;

use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaInputInterface;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaOutputInterface;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface RepositoryInterface
{
    /**
     * Get model entity by primary key
     */
    public function getByPrimaryKey(int|string $primaryKey): ModelInterface|Model|null;

    /**
     * Search entity collection by criteria
     */
    public function searchCriteria(SearchCriteriaInputInterface $input): SearchCriteriaOutputInterface;

    /**
     * Get model collection
     *
     * @return Collection<int, ModelInterface|Model>
     */
    public function collection(): Collection;

    /**
     * Create or update data model
     */
    public function createOrUpdate(ModelInterface|Model $model): ModelInterface|Model;

    /**
     * Delete model entity by primary key
     */
    public function deleteByPrimaryKey(int|string $primaryKey): bool;

    /**
     * Define model builder
     */
    public function modelBuilder(EloquentBuilder|QueryBuilder|null $modelBuilder = null): static|EloquentBuilder|QueryBuilder|null;

    /**
     * Throw if entity is null
     */
    public function throwIfNullEntity(bool|null $throwable = null): static|bool;
}
