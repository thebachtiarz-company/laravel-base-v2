<?php

namespace TheBachtiarz\Base\DTOs\Libraries\Search;

use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use TheBachtiarz\Base\DTOs\AbstractDTO;

class SimplePaginateResultDTO extends AbstractDTO
{
    public const string ITEMS = 'items';
    public const string PAGE_INFO = 'pageInfo';
    public const string TOTAL_COUNT = 'totalCount';
    public const string FILTERS = 'filters';
    public const string SORTS = 'sorts';

    /**
     * @param Collection<ModelInterface|Model|mixed> $items
     * @param SimplePaginatePageInfoDTO $pageInfo
     * @param integer $totalCount
     * @param Collection<TKey,TValue> $filters
     * @param Collection<TKey,TValue> $sorts
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
                self::FILTERS => $this->filters->all(),
                self::SORTS => $this->sorts->all(),
            ],
        );
    }

    public function toArraySimple(): array
    {
        return [
            self::ITEMS => $this->items->all(),
            self::PAGE_INFO => $this->pageInfo->toArray(),
            self::TOTAL_COUNT => $this->totalCount,
        ];
    }
}
