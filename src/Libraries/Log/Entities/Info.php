<?php

namespace TheBachtiarz\Base\Libraries\Log\Entities;

class Info extends AbstractLogEntity
{
    #[\Override]
    protected function execute(): void
    {
        $this->logger->info($this->getLogEntity());
    }
}
