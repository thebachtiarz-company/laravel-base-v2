<?php

namespace TheBachtiarz\Base\DTOs\Services;

class PaginationInputDTO
{
    public function __construct(
        public int|string|null $currentPage = null,
        public int|string|null $perPage = null,
    ) {
        $this->currentPage ??= 1;
        $this->perPage ??= 15;
    }
}
