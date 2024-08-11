<?php

namespace TheBachtiarz\Base\Http\Requests\Rules;

abstract class AbstractRule
{
    /**
     * Define rules from validation
     */
    abstract public static function rules(): array;

    /**
     * Define messages from validation
     */
    public static function messages(): array
    {
        return [];
    }
}
