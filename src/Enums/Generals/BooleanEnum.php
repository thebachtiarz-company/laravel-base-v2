<?php

namespace TheBachtiarz\Base\Enums\Generals;

use TheBachtiarz\Base\Traits\Enums\AbstractEnum;

enum BooleanEnum: int
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
            self::TRUE => 'True',
            self::FALSE => 'False',
            default => 'Unknown',
        };
    }
}
