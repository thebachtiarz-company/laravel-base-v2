<?php

namespace TheBachtiarz\Base\Enums\Generals;

use TheBachtiarz\Base\Traits\Enums\AbstractEnum;

enum ModelFilterOperatorEnum: string
{
    use AbstractEnum;

    case EQUAL = '=';
    case LIKE = 'like';
    case GREAT_THAN = '>';
    case GREAT_THAN_EQUAL = '>=';
    case LESS_THAN = '<';
    case LESS_THAN_EQUAL = '<=';

    /**
     * Get label
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::EQUAL => 'eq',
            self::LIKE => 'like',
            self::GREAT_THAN => 'gt',
            self::GREAT_THAN_EQUAL => 'gteq',
            self::LESS_THAN => 'lt',
            self::LESS_THAN_EQUAL => 'lteq',
            default => 'Unknown',
        };
    }
}
