<?php

namespace TheBachtiarz\Base\Helpers\Http\Requests;

use Illuminate\Support\Collection;

class ArrayValidateRequest
{
    protected static Collection $errorMessages;

    protected static array|string $attributeRules = ['required', 'alpha_dash:ascii'];

    protected static array|string $valueRules = ['nullable', 'string'];

    protected static string $attributeMessage = 'Format for attribute \'%s\' is incorrect';

    protected static string $valueMessage = 'Value from attribute \'%s\' is not acceptable';

    /**
     * Validate dynamic array request
     */
    public static function validate(array $data, bool $valueOnly = false): static
    {
        $errors = [];

        foreach ($data as $attribute => $value) {
            $body = $valueOnly ? compact('value') : compact('attribute', 'value');

            $rules = $valueOnly ? ['value' => static::$valueRules] : ['attribute' => static::$attributeRules, 'value' => static::$valueRules];

            $messages = $valueOnly
                ? ['value.*' => sprintf(static::$valueMessage, $attribute)]
                : ['attribute.*' => sprintf(static::$attributeMessage, $attribute), 'value.*' => sprintf(static::$valueMessage, $attribute)];

            $validate = validator(data: $body, rules: $rules, messages: $messages);

            if ($validate->fails()) {
                $errors = array_merge($errors, $validate->errors()->all());
            }
        }

        static::$errorMessages = Collection::make($errors);

        return new static();
    }

    /**
     * Is validation has error(s)
     */
    public static function hasError(): bool
    {
        try {
            static::$errorMessages->count();
        } catch (\Throwable $th) {
            static::$errorMessages = Collection::make();
        }

        return static::$errorMessages->count() > 0;
    }

    /**
     * Get error messages
     */
    public static function getErrorMessages(): Collection
    {
        try {
            static::$errorMessages->count();
        } catch (\Throwable $th) {
            static::$errorMessages = Collection::make();
        }

        return static::$errorMessages;
    }

    /**
     * Define attribute rules
     */
    public static function defineAttributeRules(array|string $rules): static
    {
        self::$attributeRules = $rules;

        return new static();
    }

    /**
     * Define value rules
     */
    public static function defineValueRules(array|string $rules): static
    {
        self::$valueRules = $rules;

        return new static();
    }

    /**
     * Define attribute message
     */
    public static function defineAttributeMessage(string $message): static
    {
        self::$attributeMessage = $message;

        return new static();
    }

    /**
     * Define value message
     */
    public static function defineValueMessage(string $message): static
    {
        self::$valueMessage = $message;

        return new static();
    }
}
