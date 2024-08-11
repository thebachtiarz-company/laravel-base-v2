<?php

namespace TheBachtiarz\Base\Helpers\General;

use TheBachtiarz\Base\Enums\Generals\ModelSortDirectionEnum;
use Illuminate\Support\Collection;

class ArrayHelper
{
    /**
     * Sort array
     */
    public static function sort(
        Collection $collection,
        string $key,
        ModelSortDirectionEnum $direction = ModelSortDirectionEnum::ASC,
    ): Collection {
        $data = $collection->all();

        usort(
            array: $data,
            callback: fn(array $a, array $b): int => match ($direction) {
                ModelSortDirectionEnum::ASC => $a[$key] <=> $b[$key],
                ModelSortDirectionEnum::DESC => $b[$key] <=> $a[$key],
            },
        );

        return new Collection($data);
    }
}
