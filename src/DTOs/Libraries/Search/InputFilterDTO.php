<?php

namespace TheBachtiarz\Base\DTOs\Libraries\Search;

use TheBachtiarz\Base\Enums\Generals\ModelFilterOperatorEnum;
use TheBachtiarz\Base\Enums\Generals\ModelFilterTypeEnum;
use Illuminate\Contracts\Support\Arrayable;

class InputFilterDTO implements Arrayable
{
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
            'column' => $this->column,
            'operator' => $this->operator->getLabel(),
            'value' => $this->value,
            'type' => $this->type->getLabel(),
        ];
    }
}
