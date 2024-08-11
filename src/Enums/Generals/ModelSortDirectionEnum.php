<?php

namespace TheBachtiarz\Base\Enums\Generals;

use TheBachtiarz\Base\Traits\Enums\AbstractEnum;

enum ModelSortDirectionEnum: string
{
    use AbstractEnum;

    case ASC = 'asc';
    case DESC = 'desc';

    /**
     * Get label
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::ASC => 'Ascending',
            self::DESC => 'Descending',
            default => 'Unknown',
        };
    }
}
