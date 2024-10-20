<?php

namespace TheBachtiarz\Base\Helpers\Http\Requests;

use Illuminate\Support\Collection;

class ArrayValidateRequest
{
    private static Collection $errorMessages;

    /**
     * Validate dynamic array request
     */
    public static function validate(array $data): static
    {
        $errors = [];

        foreach ($data as $key => $value) {
            $validate = validator(
                data: compact('key', 'value'),
                rules: [
                    'key' => ['required', 'alpha_dash:ascii'],
                    'value' => ['nullable', 'string', 'ascii'],
                ],
                messages: [
                    'key.*' => sprintf('Format for attribute \'%s\' is incorrect', $key),
                    'value.*' => sprintf('Value from attribute \'%s\' is not acceptable', $key),
                ],
            );

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
}
