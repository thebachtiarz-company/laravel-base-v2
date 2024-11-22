<?php

namespace TheBachtiarz\Base\DTOs\Libraries\Search;

use TheBachtiarz\Base\Enums\Generals\ModelSortDirectionEnum;
use TheBachtiarz\Base\DTOs\AbstractDTO;

class InputSortDTO extends AbstractDTO
{
    public const string COLUMN = 'column';
    public const string DIRECTION = 'direction';

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
            self::COLUMN => $this->column,
            self::DIRECTION => $this->direction->value,
        ];
    }
}
