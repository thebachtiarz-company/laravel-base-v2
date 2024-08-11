<?php

namespace TheBachtiarz\Base\Libraries\Curl;

use TheBachtiarz\Base\DTOs\Libraries\Curl\CurlResponseDTO;
use TheBachtiarz\Base\Interfaces\Libraries\CurlInterface;

class CurlService implements CurlInterface
{
    /**
     * Send http request
     *
     * @param class-string<AbstractCurl> $classEntity
     */
    public function sendHttpRequest(string $classEntity, array $body = [], array $headers = []): CurlResponseDTO
    {
        $curlClassEntity = app($classEntity);
        assert($curlClassEntity instanceof AbstractCurl);

        return $curlClassEntity->setHeaders(headers: $headers)->setBody(body: $body)->process();
    }
}
