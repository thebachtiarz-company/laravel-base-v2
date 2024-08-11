<?php

namespace TheBachtiarz\Base\DTOs\Libraries\Search;

use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SimplePaginateResultDTO implements Arrayable
{
    /**
     * @param Collection<ModelInterface|Model|mixed> $items
     * @param SimplePaginatePageInfoDTO $pageInfo
     * @param integer $totalCount
     * @param Collection<TKey, TValue> $filters
     * @param Collection<TKey, TValue> $sorts
     */
    public function __construct(
        public Collection $items = new Collection(),
        public SimplePaginatePageInfoDTO $pageInfo = new SimplePaginatePageInfoDTO(),
        public int $totalCount = 0,
        public Collection $filters = new Collection(),
        public Collection $sorts = new Collection(),
    ) {}

    public function toArray()
    {
        return array_merge(
            $this->toArraySimple(),
            [
                'filters' => $this->filters->all(),
                'sorts' => $this->sorts->all(),
            ],
        );
    }

    public function toArraySimple(): array
    {
        return [
            'items' => $this->items->all(),
            'page_info' => $this->pageInfo->toArray(),
            'total_count' => $this->totalCount,
        ];
    }
}
