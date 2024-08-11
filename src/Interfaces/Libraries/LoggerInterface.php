<?php

namespace TheBachtiarz\Base\Interfaces\Libraries;

interface LoggerInterface
{
    /**
     * Write logger
     */
    public function writeLog(mixed $entity, ?string $channel = null): void;
}
