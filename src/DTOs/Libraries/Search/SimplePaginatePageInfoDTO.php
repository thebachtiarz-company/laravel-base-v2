<?php

namespace TheBachtiarz\Base\DTOs\Libraries\Search;

use Illuminate\Contracts\Support\Arrayable;

class SimplePaginatePageInfoDTO implements Arrayable
{
    public function __construct(
        public int $perPage = 15,
        public int $totalPage = 1,
        public int $currentPage = 1,
    ) {}

    public function toArray()
    {
        return [
            'per_page' => $this->perPage,
            'total_page' => $this->totalPage,
            'current_page' => $this->currentPage,
        ];
    }
}
