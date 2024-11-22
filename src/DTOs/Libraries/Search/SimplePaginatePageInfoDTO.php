<?php

namespace TheBachtiarz\Base\DTOs\Libraries\Search;

use TheBachtiarz\Base\DTOs\AbstractDTO;

class SimplePaginatePageInfoDTO extends AbstractDTO
{
    /**
     * Paginate input
     *
     * @param integer $perPage
     * @param integer $totalPage
     * @param integer $currentPage
     */
    public function __construct(
        public int $perPage = 15,
        public int $totalPage = 1,
        public int $currentPage = 1,
    ) {}
}
