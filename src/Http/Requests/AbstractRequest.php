<?php

namespace TheBachtiarz\Base\Http\Requests;

use TheBachtiarz\Base\DTOs\Https\ValidatorResultDTO;
use TheBachtiarz\Base\Interfaces\Http\ValidatorBuilderInterface;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractRequest extends FormRequest
{
    /**
     * Result validator builder
     */
    private ValidatorResultDTO $validatorBuilderResult;

    /**
     * @param ValidatorBuilderInterface $validatorBuilder Validator builder
     * @param array $query The GET parameters
     * @param array $request The POST parameters
     * @param array $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array $cookies The COOKIE parameters
     * @param array $files The FILES parameters
     * @param array $server The SERVER parameters
     * @param string|resource|null $content The raw body data
     */
    public function __construct(
        protected ValidatorBuilderInterface $validatorBuilder,
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null,
    ) {
        $this->buildValidator();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,\Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->validatorBuilderResult->rules;
    }

    #[\Override]
    public function messages()
    {
        return array_merge_recursive(
            parent::messages(),
            $this->validatorBuilderResult->messages,
        );
    }

    /**
     * Generate custom validator
     */
    protected function buildValidator(): void
    {
        $this->validatorBuilderResult = $this->validatorBuilder->build();
    }
}
