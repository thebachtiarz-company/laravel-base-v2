<?php

namespace TheBachtiarz\Base\Helpers\Casts;

use TheBachtiarz\Base\Casts\UnitEnumCast;

class ModelCastHelper
{
    /**
     * Generate enum cast
     *
     * @param class-string<\UnitEnum> $unitEnum
     * @return class-string<\Illuminate\Contracts\Database\Eloquent\CastsAttributes>
     */
    public static function enumCast(string $unitEnum): string
    {
        return sprintf('%s:%s', UnitEnumCast::class, $unitEnum);
    }
}
