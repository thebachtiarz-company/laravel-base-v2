<?php

namespace TheBachtiarz\Base\Libraries\Log\Entities;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

abstract class AbstractLogEntity
{
    /**
     * Logger interface
     */
    protected LoggerInterface $logger;

    /**
     * Undocumented function
     *
     * @param string $channel Logger Channel
     * @param mixed $logEntity Logger Entity
     */
    public function __construct(
        private string $channel = 'single',
        private mixed $logEntity = null,
    ) {}

    // ? Public Methods

    /**
     * Execute process write logger
     */
    public function process(): void
    {
        $this->logger = Log::channel($this->getChannel());

        $this->execute();
    }

    // ? Protected Methods

    /**
     * Execute logger process
     */
    abstract protected function execute(): void;

    // ? Private Methods

    // ? Getter Modules

    /**
     * Get channel
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * Get log entity
     */
    public function getLogEntity(): mixed
    {
        return $this->logEntity;
    }

    // ? Setter Modules

    /**
     * Set channel
     */
    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Set log entity
     */
    public function setLogEntity($logEntity): self
    {
        $this->logEntity = $logEntity;

        return $this;
    }
}
