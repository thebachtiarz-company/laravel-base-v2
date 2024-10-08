<?php

namespace TheBachtiarz\Base\Http\Requests\Rules;

class PaginateRule extends AbstractRule
{
    public const PER_PAGE = 'perPage';
    public const CURRENT_PAGE = 'currentPage';
    public const ALL_ITEMS = 'allItems';

    #[\Override]
    public static function rules(): array
    {
        return [
            self::PER_PAGE => [
                'nullable',
                'numeric',
            ],
            self::CURRENT_PAGE => [
                'nullable',
                'numeric',
            ],
            self::ALL_ITEMS => [
                'nullable',
                'boolean',
            ],
        ];
    }

    #[\Override]
    public static function messages(): array
    {
        return [
            sprintf('%s.numeric', self::PER_PAGE) => 'Input per page should be a number',
            sprintf('%s.numeric', self::CURRENT_PAGE) => 'Input current page should be a number',
        ];
    }
}
