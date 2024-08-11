<?php

namespace TheBachtiarz\Base\Models;

use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use TheBachtiarz\Base\Traits\Models\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model implements ModelInterface
{
    use HasFactory;
    use ModelTrait;

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory()
    {
        return (new static())->modelFactory::new();
    }
}
