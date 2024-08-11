<?php

namespace TheBachtiarz\Base\DTOs\Libraries\Search;

use TheBachtiarz\Base\Enums\Generals\ModelSortDirectionEnum;
use Illuminate\Contracts\Support\Arrayable;

class InputSortDTO implements Arrayable
{
    /**
     * Model order by direction
     *
     * @param string $column
     * @param ModelSortDirectionEnum $direction
     */
    public function __construct(
        public string $column,
        public ModelSortDirectionEnum $direction = ModelSortDirectionEnum::ASC,
    ) {}

    public function toArray()
    {
        return [
            'column' => $this->column,
            'direction' => $this->direction->value,
        ];
    }
}
