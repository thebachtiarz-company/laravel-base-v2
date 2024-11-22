<?php

namespace TheBachtiarz\Base\DTOs\Libraries\Search;

use TheBachtiarz\Base\Enums\Generals\ModelFilterOperatorEnum;
use TheBachtiarz\Base\Enums\Generals\ModelFilterTypeEnum;
use TheBachtiarz\Base\DTOs\AbstractDTO;

class InputFilterDTO extends AbstractDTO
{
    public const string COLUMN = 'column';
    public const string OPERATOR = 'operator';
    public const string VALUE = 'value';
    public const string TYPE = 'type';

    /**
     * Input search filter
     *
     * @param array|string $column
     * @param ModelFilterOperatorEnum $operator
     * @param mixed $value
     * @param ModelFilterTypeEnum $type
     */
    public function __construct(
        public array|string $column,
        public ModelFilterOperatorEnum $operator = ModelFilterOperatorEnum::EQUAL,
        public mixed $value = null,
        public ModelFilterTypeEnum $type = ModelFilterTypeEnum::WHERE,
    ) {}

    public function toArray()
    {
        return [
            self::COLUMN => $this->column,
            self::OPERATOR => $this->operator->getLabel(),
            self::VALUE => $this->value,
            self::TYPE => $this->type->getLabel(),
        ];
    }
}
