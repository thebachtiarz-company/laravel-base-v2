<?php

namespace TheBachtiarz\Base\DTOs\Https;

use Illuminate\Contracts\Support\Arrayable;

class ValidatorResultDTO implements Arrayable
{
    /**
     * @param array $rules
     * @param array $messages
     */
    public function __construct(
        public readonly array $rules,
        public readonly array $messages,
    ) {}

    public function toArray()
    {
        return [
            'rules' => $this->rules,
            'messages' => $this->messages,
        ];
    }
}
