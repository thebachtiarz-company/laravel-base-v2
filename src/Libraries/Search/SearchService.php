<?php

namespace TheBachtiarz\Base\Libraries\Search;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use TheBachtiarz\Base\DTOs\Libraries\Search\InputFilterDTO;
use TheBachtiarz\Base\DTOs\Libraries\Search\InputSortDTO;
use TheBachtiarz\Base\DTOs\Libraries\Search\SimplePaginatePageInfoDTO;
use TheBachtiarz\Base\DTOs\Libraries\Search\SimplePaginateResultDTO;
use TheBachtiarz\Base\Enums\Generals\ModelFilterOperatorEnum;
use TheBachtiarz\Base\Enums\Generals\ModelFilterTypeEnum;
use TheBachtiarz\Base\Helpers\General\ModelHelper;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaInputInterface;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaInterface;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaOutputInterface;

class SearchService implements SearchCriteriaInterface
{
    /**
     * @var SearchCriteriaInputInterface
     */
    protected SearchCriteriaInputInterface $input;

    /**
     * @var EloquentBuilder|QueryBuilder
     */
    protected EloquentBuilder|QueryBuilder $builder;

    /**
     * @var LengthAwarePaginator|null
     */
    protected ?LengthAwarePaginator $result = null;

    /**
     * @var array
     */
    protected array $modelColumns = [];

    /**
     * @param SearchCriteriaOutputInterface $searchResult
     */
    public function __construct(
        protected SearchCriteriaOutputInterface $searchResult,
    ) {}

    /**
     * Execute the search criteria.
     *
     * @param SearchCriteriaInputInterface $input
     * @return SearchCriteriaOutputInterface
     */
    public function execute(SearchCriteriaInputInterface $input): SearchCriteriaOutputInterface
    {
        $this->input = $input;
        $this->result = $this->input->getCustomData();

        if (!$this->result) {
            $this->builder = $this->input->getBuilder() ?: $this->input->getModel()::query();
            $this->modelColumns = ModelHelper::getTableColumnsFromModel($this->builder->getModel());
            $this->builderFilters();
            $this->builderSorts();
            $this->searchResult->setResultOrigin(resultOrigin: clone $this->builder->get());
            $this->paginateProcess();
        }

        $this->createResultPaginate();
        return $this->searchResult;
    }

    /**
     * Apply filters to the builder.
     *
     * @return void
     */
    protected function builderFilters(): void
    {
        if ($this->input->getFilters()->count()) {
            foreach ($this->input->getFilters()->all() ?? [] as $filter) {
                assert($filter instanceof InputFilterDTO);

                if (is_array($filter->column)) {
                    if (!(new Collection($filter->column))->contains(fn(string $column): bool => in_array($column, $this->modelColumns))) {
                        continue;
                    }

                    if (!in_array($filter->type, ModelFilterTypeEnum::whereColumnArray())) {
                        $this->builder->{$filter->type->value}(
                            column: $filter->column,
                            values: $filter->value,
                        );
                        continue;
                    }

                    $this->builder->{$filter->type->value}(
                        columns: $filter->column,
                        operator: $this->builderFilterOperatorResolver($filter),
                        value: $this->builderFilterValueResolver($filter),
                    );
                    continue;
                }

                if (!in_array($filter->column, $this->modelColumns)) {
                    continue;
                }

                if (in_array($filter->type, ModelFilterTypeEnum::whereWithoutOperator())) {
                    $this->builder->{$filter->type->value}(
                        column: $filter->column,
                        values: $filter->value,
                    );
                    continue;
                }

                $this->builder->{$filter->type->value}(
                    column: $filter->column,
                    operator: $this->builderFilterOperatorResolver($filter),
                    value: $this->builderFilterValueResolver($filter),
                );
            }
        }
    }

    /**
     * Apply sorts to the builder.
     *
     * @return void
     */
    protected function builderSorts(): void
    {
        if ($this->input->getSorts()->count()) {
            foreach ($this->input->getSorts()->all() ?? [] as $sort) {
                assert($sort instanceof InputSortDTO);

                if (!in_array($sort->column, $this->modelColumns)) {
                    continue;
                }

                $this->builder->orderBy(
                    column: $sort->column,
                    direction: $sort->direction->value,
                );
            }
        }
    }

    /**
     * Process pagination.
     *
     * @return void
     */
    protected function paginateProcess(): void
    {
        $alreadyAllItems = false;
        $currentPage = null;
        $perPage = null;

        do {
            $this->result = $this->builder->paginate(
                perPage: $perPage ?? $this->input->getPerPage(),
                page: $currentPage ?? $this->input->getCurrentPage(),
            );

            if ($this->input->getIsAllItems() && !$alreadyAllItems) {
                $this->input->setPerPage($this->result->total())->setPerPage(1);
                $alreadyAllItems = true;
                $currentPage = 1;
                $perPage = $this->result->total() <= 500 ? $this->result->total() : 500;
            }

            if (count($this->result->items()) < 1 && $this->input->getCurrentPage() > 1) {
                $this->input->setCurrentPage($this->result->lastPage());
            }
        } while (count($this->result->items()) < 1 && $this->input->getCurrentPage() > 1);
    }

    /**
     * Create the result pagination.
     *
     * @return void
     */
    protected function createResultPaginate(): void
    {
        $this->searchResult->setResultPaginate(resultPaginate: new SimplePaginateResultDTO(
            items: new Collection($this->input->getMapResult()
                ? array_map(
                    callback: $this->input->getMapResult(),
                    array: $this->result->items(),
                )
                : $this->result->items()),
            pageInfo: new SimplePaginatePageInfoDTO(
                perPage: $this->result->perPage(),
                totalPage: $this->result->lastPage(),
                currentPage: $this->result->currentPage(),
            ),
            totalCount: $this->searchResult->getResultOrigin()->count() ?? $this->result->total(),
            filters: new Collection(array_map(
                callback: fn(InputFilterDTO $filter): array => $filter->toArray(),
                array: $this->input->getFilters()->all(),
            )),
            sorts: new Collection(array_map(
                callback: fn(InputSortDTO $sort): array => $sort->toArray(),
                array: $this->input->getSorts()->all(),
            )),
        ));
    }

    /**
     * Resolve the filter operator.
     *
     * @param InputFilterDTO $filter
     * @return string
     */
    private function builderFilterOperatorResolver(InputFilterDTO $filter): string
    {
        return $filter->operator->value;
    }

    /**
     * Resolve the filter value.
     *
     * @param InputFilterDTO $filter
     * @return mixed
     */
    private function builderFilterValueResolver(InputFilterDTO $filter): mixed
    {
        return match ($filter->operator) {
            ModelFilterOperatorEnum::LIKE => "%$filter->value%",
            default => $filter->value,
        };
    }
}
