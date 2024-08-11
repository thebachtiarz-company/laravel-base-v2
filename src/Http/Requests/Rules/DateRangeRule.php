<?php

namespace TheBachtiarz\Base\Http\Requests\Rules;

class DateRangeRule extends AbstractRule
{
    public const DATE_FROM = 'dateFrom';
    public const DATE_TO = 'dateTo';

    #[\Override]
    public static function rules(): array
    {
        return [
            self::DATE_FROM => [
                'nullable',
                'date_format:Y-m-d',
            ],
            self::DATE_TO => [
                'nullable',
                'date_format:Y-m-d',
            ],
        ];
    }

    #[\Override]
    public static function messages(): array
    {
        return [
            sprintf('%s.date_format', self::DATE_FROM) => 'Date From format invalid! (valid format: 2024-08-17)',
            sprintf('%s.date_format', self::DATE_TO) => 'Date To format invalid! (valid format: 2024-08-17)',
        ];
    }
}
