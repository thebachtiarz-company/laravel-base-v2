<?php

namespace TheBachtiarz\Base\Helpers\Http;

use TheBachtiarz\Base\DTOs\Https\ValidatorResultDTO;
use TheBachtiarz\Base\Http\Requests\Rules\AbstractRule;
use TheBachtiarz\Base\Interfaces\Http\ValidatorBuilderInterface;
use Illuminate\Support\Collection;

class ValidatorBuilderHelper implements ValidatorBuilderInterface
{
    /**
     * Validator builder result
     */
    public ValidatorResultDTO $result;

    /**
     * @param Collection $rules
     * @param Collection $messages
     */
    public function __construct(
        private Collection $rules = new Collection(),
        private Collection $messages = new Collection(),
    ) {
        $this->result = new ValidatorResultDTO(rules: [], messages: []);
    }

    /**
     * Add rule(s) in validator.
     *
     * Rule Example: ['input' => ['required','string']].
     * Result Example: ['input' => 'required|string'].
     *
     * @param string|null $prefix Add prefix in attribute | Result Example: ['prefixinput' => 'required|string']
     */
    public function addRules(AbstractRule $rule, ?string $prefix = null): self
    {
        foreach ($rule::rules() as $input => $validations) {
            try {
                $validations = implode(separator: '|', array: $validations);
            } catch (\Throwable $th) {
            }

            $this->rules->put(key: sprintf('%s%s', $prefix, $input), value: $validations);
        }

        foreach ($rule::messages() as $input => $message) {
            $this->messages->put(key: sprintf('%s%s', $prefix, $input), value: $message);
        }

        return $this;
    }

    /**
     * Add rule(s) in nested prefix.
     *
     * Rule Example: ['input' => ['required','string']].
     * Prefix Example: 'items'.
     * Result Example: ['items.*.input' => 'required|string'].
     *
     * @param boolean $withRuleKey Add rule params in validator | false: ['items.*' => 'required|string']
     */
    public function addNestedRules(AbstractRule $rule, string $prefix, bool $withRuleKey = true): self
    {
        foreach ($rule::rules() as $input => $validations) {
            try {
                $validations = implode(separator: '|', array: $validations);
            } catch (\Throwable $th) {
            }

            $this->rules->put(key: sprintf('%s.*%s', $prefix, $withRuleKey ? ".$input" : ''), value: $validations);
        }

        foreach ($rule::messages() as $input => $message) {
            $this->messages->put(key: sprintf('%s.*%s', $prefix, $withRuleKey ? ".$input" : ''), value: $message);
        }

        return $this;
    }

    /**
     * Remove rule in validator.
     *
     * Validators Example: ['product' => 'nullable|json', 'input' => 'required|string'].
     * Result Example: ['product' => 'nullable|json'].
     */
    public function removeRule(string $input): self
    {
        $this->rules->forget(keys: $input);
        $this->messages->forget(keys: $input);

        return $this;
    }

    /**
     * Build validator rules
     */
    public function build(): ValidatorResultDTO
    {
        return new ValidatorResultDTO(
            rules: $this->rules->all(),
            messages: $this->messages->all(),
        );
    }
}
