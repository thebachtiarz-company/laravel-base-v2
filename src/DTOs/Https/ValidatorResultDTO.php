<?php

namespace TheBachtiarz\Base\DTOs\Https;

use TheBachtiarz\Base\DTOs\AbstractDTO;

class ValidatorResultDTO extends AbstractDTO
{
    /**
     * @param array $rules
     * @param array $messages
     */
    public function __construct(
        public readonly array $rules,
        public readonly array $messages,
    ) {}
}
