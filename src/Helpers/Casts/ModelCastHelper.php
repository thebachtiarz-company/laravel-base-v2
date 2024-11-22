<?php

namespace TheBachtiarz\Base\Helpers\Casts;

use TheBachtiarz\Base\Casts\DataTransferObjectCast;
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

    /**
     * Generate data transfer object cast
     *
     * @param class-string<\TheBachtiarz\Base\DTOs\AbstractDTO> $dtoClass
     * @return class-string<\Illuminate\Contracts\Database\Eloquent\CastsAttributes>
     */
    public static function dtoCast(string $dtoClass): string
    {
        return sprintf('%s:%s', DataTransferObjectCast::class, $dtoClass);
    }
}
