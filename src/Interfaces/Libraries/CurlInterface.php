<?php

namespace TheBachtiarz\Base\Interfaces\Libraries;

use TheBachtiarz\Base\DTOs\Libraries\Curl\CurlResponseDTO;

interface CurlInterface
{
    /**
     * Send http request
     */
    public function sendHttpRequest(string $classEntity, array $body = [], array $headers = []): CurlResponseDTO;
}
