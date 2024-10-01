<?php

namespace TheBachtiarz\Base\DTOs\Models;

use Illuminate\Contracts\Support\Arrayable;

class ModelComponentDTO implements Arrayable
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

    public function toArray()
    {
        return [
            'className' => $this->className,
            'attributes' => $this->attributes,
        ];
    }
}
