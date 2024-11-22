<?php

namespace TheBachtiarz\Base\DTOs\Models;

use TheBachtiarz\Base\DTOs\AbstractDTO;

class ModelComponentDTO extends AbstractDTO
{
    /**
     * Class attribute
     *
     * @param class-string<Model> $className
     * @param array $attributes Entity as Array
     */
    public function __construct(
        public readonly string $className,
        public readonly array $attributes,
    ) {}
}
