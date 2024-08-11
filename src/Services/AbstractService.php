<?php

namespace TheBachtiarz\Base\Services;

use TheBachtiarz\Base\DTOs\Services\ResponseDataDTO;
use TheBachtiarz\Base\Helpers\Services\ResponseCollectionHelper;
use TheBachtiarz\Base\Interfaces\Libraries\LoggerInterface;
use TheBachtiarz\Base\Interfaces\Services\ServiceInterface;

abstract class AbstractService implements ServiceInterface
{
    /**
     * Service will ignore response result
     */
    private bool $withoutResponse = false;

    /**
     * Service will ignore error logging
     */
    private bool $ignoreLogError = false;

    /**
     * @param ResponseDataDTO $response
     */
    public function __construct(
        protected ResponseDataDTO $response = new ResponseDataDTO(),
    ) {}

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->response = new ResponseDataDTO();
    }

    // ? Public Methods

    /**
     * Get current response
     */
    public function getResponse(?string $uniqueCode = null): ResponseDataDTO
    {
        $responses = ResponseCollectionHelper::getResponses();

        $result = $uniqueCode ? $responses->get($uniqueCode) : $responses->last();

        return $result ?? $this->response;
    }

    /**
     * Set new response
     */
    public function setResponse(ResponseDataDTO $response): static
    {
        if ($this->withoutResponse) {
            $this->withoutResponse = false;

            return $this;
        }

        ResponseCollectionHelper::add(data: $response, key: static::class);

        return $this;
    }

    /**
     * Get all response
     *
     * @return ResponseDataDTO[]
     */
    public function getAllResponse(): array
    {
        return ResponseCollectionHelper::getResponses()->all();
    }

    /**
     * Set current process without response
     */
    public function withoutResponse(): static
    {
        $this->withoutResponse = true;

        return $this;
    }

    /**
     * Ignore any error log
     */
    public function ignoreWriteLog(): static
    {
        $this->ignoreLogError = true;

        return $this;
    }

    /**
     * Get response unique code
     */
    public function getResponseUniqueCode(): string
    {
        return sprintf(
            ResponseCollectionHelper::getUniqueCodeTemplate(),
            static::class,
            ResponseCollectionHelper::getCurrentUniqueCode(),
        );
    }

    // ? Protected Methods

    /**
     * Create error logger
     *
     * @param string|null $channel Default: developer
     */
    final protected function log(mixed $log, string|null $channel = null): void
    {
        if ($this->ignoreLogError) {
            $this->ignoreLogError = false;

            return;
        }

        /** @var LoggerInterface $logger */
        $logger = app(LoggerInterface::class);

        $logger->writeLog(entity: $log, channel: $channel);
    }

    // ? Private Methods

    // ? Getter Modules

    // ? Setter Modules
}
