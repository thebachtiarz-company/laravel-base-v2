<?php

namespace TheBachtiarz\Base\DTOs;

use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractDTO implements Arrayable
{
    /**
     * Set object data from array
     *
     * @param array<string,mixed> $data
     * @return static
     */
    public function fromArray(array $data): static
    {
        foreach (get_class_vars(static::class) as $attribute => $def) {
            $this->{$attribute} = @$data[$attribute];
        }

        return $this;
    }

    public function toArray()
    {
        $result = [];

        foreach (get_class_vars(static::class) as $attribute => $def) {
            $result[$attribute] = $this->{$attribute};
        }

        return $result;
    }
}
