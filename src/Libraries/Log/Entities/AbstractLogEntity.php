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
     * Constructor
     * 
     * @param string $channel Logger Channel
     * @param mixed $logEntity Logger Entity
     */
    public function __construct(
        private string $channel = 'single',
        private mixed $logEntity = null,
    ) {}

    /**
     * Execute process write logger
     */
    public function process(): void
    {
        $this->logger = Log::channel($this->getChannel());
        $this->execute();
    }

    /**
     * Execute logger process
     */
    abstract protected function execute(): void;

    /**
     * Get channel
     * 
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * Get log entity
     * 
     * @return mixed
     */
    public function getLogEntity(): mixed
    {
        return $this->logEntity;
    }

    /**
     * Set channel
     * 
     * @param string $channel
     * @return self
     */
    public function setChannel(string $channel): self
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * Set log entity
     * 
     * @param mixed $logEntity
     * @return self
     */
    public function setLogEntity($logEntity): self
    {
        $this->logEntity = $logEntity;
        return $this;
    }
}
