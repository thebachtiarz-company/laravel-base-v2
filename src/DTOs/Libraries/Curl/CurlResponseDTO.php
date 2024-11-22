<?php

namespace TheBachtiarz\Base\DTOs\Libraries\Curl;

use TheBachtiarz\Base\DTOs\AbstractDTO;

class CurlResponseDTO extends AbstractDTO
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
}
