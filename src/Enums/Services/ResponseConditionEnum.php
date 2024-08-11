<?php

namespace TheBachtiarz\Base\Enums\Services;

use TheBachtiarz\Base\Traits\Enums\AbstractEnum;

enum ResponseConditionEnum: int
{
    use AbstractEnum;

    case TRUE = 1;
    case FALSE = 0;

    /**
     * Get as boolean
     */
    public function toBoolean(): bool
    {
        return match ($this) {
            self::TRUE => true,
            self::FALSE => false,
            default => false,
        };
    }

    /**
     * Get label
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::TRUE => 'isTrue',
            self::FALSE => 'isFalse',
            default => 'Unknown',
        };
    }
}
