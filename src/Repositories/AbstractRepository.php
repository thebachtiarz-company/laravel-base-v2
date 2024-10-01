<?php

namespace TheBachtiarz\Base\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;
use TheBachtiarz\Base\Exceptions\BaseException;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaInputInterface;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaInterface;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaOutputInterface;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use TheBachtiarz\Base\Interfaces\Repositories\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * Model entity
     */
    protected ModelInterface|Model $modelEntity;

    /**
     * Query Search
     */
    protected SearchCriteriaInterface $searchCriteria;

    /**
     * Model builder
     */
    protected EloquentBuilder|QueryBuilder|null $modelBuilder = null;

    /**
     * Model data
     *
     * @var array
     */
    protected array $modelData = [];

    /**
     * Throw if entity is null
     */
    protected bool $throwIfNullEntity = true;

    /**
     * Model entity name
     */
    protected ?string $modelEntityName = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->searchCriteria = app(SearchCriteriaInterface::class);
        $this->modelEntityName();
    }

    // ? Public Methods

    /**
     * Get model entity by primary key
     */
    public function getByPrimaryKey(int|string $primaryKey): ModelInterface|Model|null
    {
        $this->modelBuilder(
            modelBuilder: $this->modelEntity::query()->where(
                column: $this->modelEntity->getPrimaryKeyAttribute(),
                operator: '=',
                value: $primaryKey,
            ),
        );

        $entity = $this->modelBuilder()->first();

        if (!$entity && $this->throwIfNullEntity()) {
            throw new BaseException(
                message: sprintf(
                    $this->getByIdErrorMessage(),
                    $this->modelEntityName,
                    $primaryKey,
                ),
                code: 404,
            );
        }

        return $entity;
    }

    /**
     * Search entity collection by criteria
     */
    public function searchCriteria(SearchCriteriaInputInterface $input): SearchCriteriaOutputInterface
    {
        $input->setModel(model: $this->modelEntity);

        if (!$input->getMapResult()) {
            $input->setMapResult(map: fn(ModelInterface|Model $model): array => $model->simpleListMap());
        }

        return $this->searchCriteria->execute(input: $input);
    }

    /**
     * Get model collection
     *
     * @return Collection<int, ModelInterface|Model>
     */
    public function collection(): Collection
    {
        return $this->modelEntity::all();
    }

    /**
     * Create or update data model
     */
    public function createOrUpdate(ModelInterface|Model $model): ModelInterface|Model
    {
        if ($model->getId()) {
            $this->entityUpdate(model: $model);
        } else {
            $this->entityCreate(model: $model);
        }

        return $model;
    }

    /**
     * Delete model entity by primary key
     */
    public function deleteByPrimaryKey(int|string $primaryKey): bool
    {
        return $this->getByPrimaryKey(primaryKey: $primaryKey)?->delete();
    }

    /**
     * Define model builder
     */
    public function modelBuilder(EloquentBuilder|QueryBuilder|null $modelBuilder = null): static|EloquentBuilder|QueryBuilder|null
    {
        if (!is_null($modelBuilder)) {
            $this->modelBuilder = $modelBuilder;

            return $this;
        }

        return $this->modelBuilder;
    }

    /**
     * Throw if entity is null
     */
    public function throwIfNullEntity(bool|null $throwable = null): static|bool
    {
        if (!is_null($throwable)) {
            $this->throwIfNullEntity = $throwable;

            return $this;
        }

        return $this->throwIfNullEntity;
    }

    // ? Protected Methods

    /**
     * Set model class entity
     *
     * @param class-string<ModelInterface|Model> $class
     */
    protected function setModelEntity(string $class): static
    {
        $this->modelEntity = app($class);

        return $this;
    }

    /**
     * Process entity create
     */
    protected function entityCreate(ModelInterface|Model &$model): void
    {
        $model = $this->createFromModel(model: $model);

        if (!$model) {
            throw new Exception(sprintf(
                $this->createOrUpdateErrorMessage(),
                'create new',
                $this->modelEntityName,
            ));
        }
    }

    /**
     * Process entity update
     */
    protected function entityUpdate(ModelInterface|Model &$model): void
    {
        $save = $model->save();

        if (!$save) {
            throw new Exception(sprintf(
                $this->createOrUpdateErrorMessage(),
                'update',
                $this->modelEntityName,
            ));
        }
    }

    /**
     * Create new record from model
     */
    protected function createFromModel(ModelInterface|Model $model): ModelInterface|Model
    {
        return $model::create($this->prepareCreate(model: $model));
    }

    /**
     * Prepare data create
     */
    protected function prepareCreate(ModelInterface|Model $model): array
    {
        foreach ($model->getFillable() as $key => $attribute) {
            $this->modelData[$attribute] = $model->__get(key: $attribute);
        }

        return $this->modelData;
    }

    /**
     * Define model entity name
     */
    protected function modelEntityName(?string $name = null): static
    {
        $this->modelEntityName ??= $name ?? Str::headline(class_basename($this->modelEntity::class));

        return $this;
    }

    /**
     * Define get by id error message
     */
    protected function getByIdErrorMessage(): string
    {
        return "%s with key '%s' not found!";
    }

    /**
     * Define create or update error message
     */
    protected function createOrUpdateErrorMessage(): string
    {
        return "Failed to %s %s";
    }

    // ? Private Methods

    // ? Getter Modules

    // ? Setter Modules
}
