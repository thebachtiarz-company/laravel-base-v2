<?php

namespace TheBachtiarz\Base\Interfaces\Services;

use TheBachtiarz\Base\DTOs\Services\ResponseDataDTO;

interface ServiceInterface
{
    /**
     * Get current response
     */
    public function getResponse(?string $uniqueCode = null): ResponseDataDTO;

    /**
     * Set new response
     */
    public function setResponse(ResponseDataDTO $response): static;

    /**
     * Get all response
     *
     * @return ResponseDataDTO[]
     */
    public function getAllResponse(): array;

    /**
     * Set current process without response
     */
    public function withoutResponse(): static;

    /**
     * Get response unique code
     */
    public function getResponseUniqueCode(): string;
}
