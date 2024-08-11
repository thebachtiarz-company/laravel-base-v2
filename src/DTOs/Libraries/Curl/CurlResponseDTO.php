<?php

namespace TheBachtiarz\Base\DTOs\Libraries\Curl;

use TheBachtiarz\Base\Interfaces\Http\ResponseInterface;
use Illuminate\Contracts\Support\Arrayable;

class CurlResponseDTO implements Arrayable
{
    /**
     * @param integer $httpCode
     * @param string $status
     * @param string $message
     * @param mixed $data
     */
    public function __construct(
        public int $httpCode = 200,
        public string $status = 'error',
        public string $message = '',
        public mixed $data = null,
    ) {}

    public function toArray()
    {
        return [
            ResponseInterface::HTTP_CODE => $this->httpCode,
            ResponseInterface::STATUS => $this->status,
            ResponseInterface::MESSAGE => $this->message,
            ResponseInterface::DATA => $this->data,
        ];
    }
}
