<?php

namespace TheBachtiarz\Base\Interfaces\Libraries;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use TheBachtiarz\Base\DTOs\Libraries\Search\InputSortDTO;
use TheBachtiarz\Base\DTOs\Libraries\Search\InputFilterDTO;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;

interface SearchCriteriaInputInterface
{
    // ? Getter Modules

    /**
     * Get the value of model
     */
    public function getModel(): ModelInterface|Model|null;

    /**
     * Get the value of builder
     */
    public function getBuilder(): EloquentBuilder|QueryBuilder|null;

    /**
     * Get the value of customData
     */
    public function getCustomData(): ?LengthAwarePaginator;

    /**
     * Get the value of filters
     *
     * @return Collection<InputFilterDTO>
     */
    public function getFilters(): Collection;

    /**
     * Get the value of sorts
     *
     * @return Collection<InputSortDTO>
     */
    public function getSorts(): Collection;

    /**
     * Get the value of map result
     */
    public function getMapResult(): ?Closure;

    /**
     * Get the value of perPage
     */
    public function getPerPage(): int;

    /**
     * Get the value of currentPage
     */
    public function getCurrentPage(): int;

    /**
     * Get is all items
     */
    public function getIsAllItems(): bool;

    // ? Setter Modules

    /**
     * Set the value of model
     */
    public function setModel(ModelInterface|Model $model): self;

    /**
     * Set the value of builder
     */
    public function setBuilder(EloquentBuilder|QueryBuilder $builder): self;

    /**
     * Set the value of customData
     */
    public function setCustomData(LengthAwarePaginator $customData): self;

    /**
     * Set the value of filters
     *
     * @param Collection<InputFilterDTO> $filters
     * @return self
     */
    public function setFilters(Collection $filters): self;

    /**
     * Set the value of sorts
     *
     * @param Collection<InputSortDTO> $sorts
     * @return self
     */
    public function setSorts(Collection $sorts): self;

    /**
     * Set the value of map result
     */
    public function setMapResult(Closure $map): self;

    /**
     * Set the value of perPage
     */
    public function setPerPage(int $perPage): self;

    /**
     * Set the value of currentPage
     */
    public function setCurrentPage(int $currentPage): self;

    /**
     * Set is all items
     */
    public function setIsAllItems(bool $allItems = false): self;
}
