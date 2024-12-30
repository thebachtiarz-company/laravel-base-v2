<?php

namespace TheBachtiarz\Base\Enums\Services;

use TheBachtiarz\Base\Traits\Enums\AbstractEnum;

enum ResponseHttpCodeEnum: int
{
    use AbstractEnum;

    case OK = 200;
    case CREATED = 201;
    case ACCEPTED = 202;
    case NO_CONTENT = 204;

    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case NOT_FOUND = 404;
    case UNPROCESSABLE = 422;

    case INTERNAL_SERVER_ERROR = 500;
    case NOT_IMPLEMENTED = 501;
    case SERVICE_UNAVAILABLE = 503;

    /**
     * Get label
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::OK => 'OK',
            self::CREATED => 'Created',
            self::ACCEPTED => 'Accepted',
            self::NO_CONTENT => 'No Content',
            self::BAD_REQUEST => 'Bad Request',
            self::UNAUTHORIZED => 'Unauthorized',
            self::NOT_FOUND => 'Not Found',
            self::UNPROCESSABLE => 'Unprocessable',
            self::INTERNAL_SERVER_ERROR => 'Internal Server Error',
            self::NOT_IMPLEMENTED => 'Not Implemented',
            self::SERVICE_UNAVAILABLE => 'Service Unavailable',
            default => 'Unknown',
        };
    }
}
