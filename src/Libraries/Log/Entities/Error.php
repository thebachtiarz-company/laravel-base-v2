<?php

namespace TheBachtiarz\Base\Libraries\Log\Entities;

use Throwable;

class Error extends AbstractLogEntity
{
    #[\Override]
    protected function execute(): void
    {
        $throwable = $this->getLogEntity();
        assert($throwable instanceof Throwable);

        $trace = $throwable->getTrace();

        $logData = json_encode([
            'file' => $trace[0]['file'],
            'line' => $trace[0]['line'],
            'message' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
        ]);

        $this->logger->error($logData);
    }
}
