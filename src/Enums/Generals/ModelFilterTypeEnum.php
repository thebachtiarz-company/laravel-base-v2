<?php

namespace TheBachtiarz\Base\Enums\Generals;

use TheBachtiarz\Base\Traits\Enums\AbstractEnum;

enum ModelFilterTypeEnum: string
{
    use AbstractEnum;

    case WHERE = 'where';
    case WHERE_DATE = 'whereDate';
    case WHERE_IN = 'whereIn';
    case WHERE_NOT = 'whereNot';
    case WHERE_NOT_IN = 'whereNotIn';
    case WHERE_ANY = 'whereAny';
    case WHERE_ALL = 'whereAll';

    /**
     * Filter where with operator
     */
    public static function whereWithOperator(): array
    {
        return [
            self::WHERE,
            self::WHERE_DATE,
            self::WHERE_ANY,
            self::WHERE_ALL,
        ];
    }

    /**
     * Filter where without operator
     */
    public static function whereWithoutOperator(): array
    {
        return [
            self::WHERE_IN,
            self::WHERE_NOT,
            self::WHERE_NOT_IN,
        ];
    }

    /**
     * Filter where column array
     */
    public static function whereColumnArray(): array
    {
        return [
            self::WHERE_ANY,
            self::WHERE_ALL,
        ];
    }
}
