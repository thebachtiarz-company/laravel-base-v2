<?php

namespace TheBachtiarz\Base\Enums\Services;

use TheBachtiarz\Base\Traits\Enums\AbstractEnum;

enum ResponseStatusEnum: string
{
    use AbstractEnum;

    case SUCCESS = 'success';
    case ERROR = 'error';
}
