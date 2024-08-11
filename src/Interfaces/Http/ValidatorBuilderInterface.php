<?php

namespace TheBachtiarz\Base\Interfaces\Http;

use TheBachtiarz\Base\DTOs\Https\ValidatorResultDTO;
use TheBachtiarz\Base\Http\Requests\Rules\AbstractRule;

interface ValidatorBuilderInterface
{
    /**
     * Add rule(s) in validator.
     *
     * Rule Example: ['input' => ['required','string']].
     * Result Example: ['input' => 'required|string'].
     *
     * @param string|null $prefix Add prefix in attribute | Result Example: ['prefixinput' => 'required|string']
     */
    public function addRules(AbstractRule $rule, ?string $prefix = null): self;

    /**
     * Add rule(s) in nested prefix.
     *
     * Rule Example: ['input' => ['required','string']].
     * Prefix Example: 'items'.
     * Result Example: ['items.*.input' => 'required|string'].
     *
     * @param boolean $withRuleKey Add rule params in validator | false: ['items.*' => 'required|string']
     */
    public function addNestedRules(AbstractRule $rule, string $prefix, bool $withRuleKey = true): self;

    /**
     * Remove rule in validator.
     *
     * Validators Example: ['product' => 'nullable|json', 'input' => 'required|string'].
     * Result Example: ['product' => 'nullable|json'].
     */
    public function removeRule(string $input): self;

    /**
     * Build validator rules
     */
    public function build(): ValidatorResultDTO;
}
