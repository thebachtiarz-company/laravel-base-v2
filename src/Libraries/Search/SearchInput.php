<?php

namespace TheBachtiarz\Base\Libraries\Search;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use TheBachtiarz\Base\DTOs\Libraries\Search\InputSortDTO;
use TheBachtiarz\Base\DTOs\Libraries\Search\InputFilterDTO;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaInputInterface;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;

class SearchInput implements SearchCriteriaInputInterface
{
    /**
     * @param ModelInterface|Model|null $model
     * @param EloquentBuilder|QueryBuilder|null|null $builder
     * @param LengthAwarePaginator|null $customData
     * @param Collection<InputFilterDTO> $filters
     * @param Collection<InputSortDTO> $sorts
     * @param Closure|null $mapResult
     * @param integer $perPage
     * @param integer $currentPage
     * @param boolean $isAllItems
     */
    public function __construct(
        protected ModelInterface|Model|null $model = null,
        protected EloquentBuilder|QueryBuilder|null $builder = null,
        protected ?LengthAwarePaginator $customData = null,
        protected Collection $filters = new Collection(),
        protected Collection $sorts = new Collection(),
        protected ?Closure $mapResult = null,
        protected int $perPage = 15,
        protected int $currentPage = 1,
        protected bool $isAllItems = false,
    ) {}

    // ? Public Methods

    // ? Protected Methods

    // ? Private Methods

    // ? Getter Modules

    /**
     * Get the value of model
     */
    public function getModel(): ModelInterface|Model|null
    {
        return $this->model;
    }

    /**
     * Get the value of builder
     */
    public function getBuilder(): EloquentBuilder|QueryBuilder|null
    {
        return $this->builder;
    }

    /**
     * Get the value of custom data
     */
    public function getCustomData(): ?LengthAwarePaginator
    {
        return $this->customData;
    }

    /**
     * Get the value of filters
     *
     * @return Collection<InputFilterDTO>
     */
    public function getFilters(): Collection
    {
        return $this->filters;
    }

    /**
     * Get the value of sorts
     *
     * @return Collection<InputSortDTO>
     */
    public function getSorts(): Collection
    {
        return $this->sorts;
    }

    /**
     * Get the value of map result
     */
    public function getMapResult(): ?Closure
    {
        return $this->mapResult;
    }

    /**
     * Get the value of perPage
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Get the value of currentPage
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Get the value of isAllItems
     */
    public function getIsAllItems(): bool
    {
        return $this->isAllItems;
    }

    // ? Setter Modules

    /**
     * Set the value of model
     */
    public function setModel(ModelInterface|Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Set the value of builder
     */
    public function setBuilder(EloquentBuilder|QueryBuilder $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * Set the value of custom data
     */
    public function setCustomData(LengthAwarePaginator $customData): self
    {
        $this->customData = $customData;

        return $this;
    }

    /**
     * Set the value of filters
     *
     * @param Collection<InputFilterDTO> $filters
     * @return self
     */
    public function setFilters(Collection $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Set the value of sorts
     *
     * @param Collection<InputSortDTO> $sorts
     * @return self
     */
    public function setSorts(Collection $sorts): self
    {
        $this->sorts = $sorts;

        return $this;
    }

    /**
     * Set the value of map result
     */
    public function setMapResult(Closure $map): self
    {
        $this->mapResult = $map;

        return $this;
    }

    /**
     * Set the value of perPage
     */
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Set the value of currentPage
     */
    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
     * Set the value of isAllItems
     */
    public function setIsAllItems(bool $isAllItems = false): self
    {
        $this->isAllItems = $isAllItems;

        return $this;
    }
}
