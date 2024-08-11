<?php

namespace TheBachtiarz\Base\Libraries\Search;

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
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

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
     * @var string[]
     */
    protected array $modelColumns = [];

    /**
     * @param SearchCriteriaOutputInterface $searchResult
     */
    public function __construct(
        protected SearchCriteriaOutputInterface $searchResult,
    ) {}

    // ? Public Methods

    /**
     * Execute search criteria
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

    // ? Protected Methods

    /**
     * Add filter(s) in builder
     */
    protected function builderFilters(): void
    {
        if ($this->input->getFilters()->count()) {
            foreach ($this->input->getFilters()->all() ?? [] as $key => $filter) {
                assert($filter instanceof InputFilterDTO);

                FILTER_COLUMN_ARRAY:
                if (is_array($filter->column)) {
                    if (!(new Collection($filter->column))->contains(fn(string $column): bool => in_array(needle: $column, haystack: $this->modelColumns))) {
                        goto FILTER_CONTINUE;
                    }

                    if (!in_array(needle: $filter->type, haystack: ModelFilterTypeEnum::whereColumnArray())) {
                        goto FILTER_WITHOUT_OPERATOR;
                    }

                    $this->builder->{$filter->type->value}(
                        columns: $filter->column,
                        operator: $this->builderFilterOperatorResolver($filter),
                        value: $this->builderFilterValueResolver($filter),
                    );

                    goto FILTER_CONTINUE;
                }

                if (!in_array(needle: $filter->column, haystack: $this->modelColumns)) {
                    goto FILTER_CONTINUE;
                }

                FILTER_WITHOUT_OPERATOR:
                if (in_array(needle: $filter->type, haystack: ModelFilterTypeEnum::whereWithoutOperator())) {
                    $this->builder->{$filter->type->value}(
                        column: $filter->column,
                        values: $filter->value,
                    );

                    goto FILTER_CONTINUE;
                }

                FILTER_WITH_OPERATOR:
                $this->builder->{$filter->type->value}(
                    column: $filter->column,
                    operator: $this->builderFilterOperatorResolver($filter),
                    value: $this->builderFilterValueResolver($filter),
                );

                FILTER_CONTINUE:
            }
        }
    }

    /**
     * Add sort(s) in builder
     */
    protected function builderSorts(): void
    {
        if ($this->input->getSorts()->count()) {
            foreach ($this->input->getSorts()->all() ?? [] as $kSort => $sort) {
                assert($sort instanceof InputSortDTO);

                if (!in_array(needle: $sort->column, haystack: $this->modelColumns)) {
                    goto SORT_CONTINUE;
                }

                $this->builder->orderBy(
                    column: $sort->column,
                    direction: $sort->direction->value,
                );

                SORT_CONTINUE:
            }
        }
    }

    /**
     * Patch pagination result
     */
    protected function paginateProcess(): void
    {
        PROCESS_PAGINATE:
        $this->result = $this->builder->paginate(
            perPage: $this->input->getPerPage(),
            page: $this->input->getCurrentPage(),
        );

        if (count($this->result->items()) < 1 && $this->input->getCurrentPage() > 1) {
            $this->input->setCurrentPage($this->result->lastPage());
            goto PROCESS_PAGINATE;
        }
    }

    /**
     * Create pagination result
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

    // ? Private Methods

    /**
     * Resolve filter operator builder
     */
    private function builderFilterOperatorResolver(InputFilterDTO $filter): string
    {
        return $filter->operator->value;
    }

    /**
     * Resolve filter value builder
     */
    private function builderFilterValueResolver(InputFilterDTO $filter): mixed
    {
        return match ($filter->operator) {
            ModelFilterOperatorEnum::LIKE => "%$filter->value%",
            default => $filter->value,
        };
    }

    // ? Getter Modules

    // ? Setter Modules
}
