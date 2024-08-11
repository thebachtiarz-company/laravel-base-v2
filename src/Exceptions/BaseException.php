<?php

namespace TheBachtiarz\Base\Exceptions;

use TheBachtiarz\Base\Interfaces\Libraries\LoggerInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BaseException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void
    {
        /** @var LoggerInterface $logger */
        $logger = app(LoggerInterface::class);

        $logger->writeLog(entity: $this, channel: 'error');
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): Response
    {
        $message = match ($this->getCode()) {
            401, 402, 403, 404, 405 => $this->getMessage(),
            default => 'Something went wrong, please try again later.',
        };

        $code = $this->getCode() > 0 ? $this->getCode() : 403;

        return response(content: compact('message'), status: $code);
    }
}
