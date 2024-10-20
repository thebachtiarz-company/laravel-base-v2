<?php

namespace TheBachtiarz\Base\Helpers\Http;

use TheBachtiarz\Base\DTOs\Https\ValidatorResultDTO;
use TheBachtiarz\Base\Http\Requests\Rules\AbstractRule;
use TheBachtiarz\Base\Interfaces\Http\ValidatorBuilderInterface;
use Illuminate\Support\Collection;

class ValidatorBuilderHelper implements ValidatorBuilderInterface
{
    public ValidatorResultDTO $result;

    public function __construct(
        private Collection $rules = new Collection(),
        private Collection $messages = new Collection(),
    ) {
        $this->result = new ValidatorResultDTO(rules: [], messages: []);
    }

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

    public function modifyRule(string $input, string $rule, string $new): self
    {
        if (in_array($input, array_keys($this->rules->toArray()))) {
            $inputRules = $this->rules->get($input);
            $isString = gettype($inputRules) === 'string';

            $inputRules = $isString ? explode(separator: '|', string: $inputRules) : $inputRules;

            foreach ($inputRules as $key => &$value) {
                if (gettype($value) === 'string') {
                    if ($value === $rule) {
                        $value = $new;

                        break;
                    }
                }
            }

            $this->rules->put($input, $isString ? implode(separator: '|', array: $inputRules) : $inputRules);
        }

        return $this;
    }

    public function removeRule(string $input): self
    {
        $this->rules->forget(keys: $input);
        $this->messages->forget(keys: $input);

        return $this;
    }

    public function build(): ValidatorResultDTO
    {
        return new ValidatorResultDTO(
            rules: $this->rules->all(),
            messages: $this->messages->all(),
        );
    }

    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function setRules(Collection $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    public function setMessages(Collection $messages): self
    {
        $this->messages = $messages;

        return $this;
    }
}
