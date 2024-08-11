<?php

namespace TheBachtiarz\Base\Libraries\Curl;

use TheBachtiarz\Base\DTOs\Libraries\Curl\CurlResponseDTO;
use TheBachtiarz\Base\Enums\Services\ResponseHttpCodeEnum;
use TheBachtiarz\Base\Enums\Services\ResponseStatusEnum;
use TheBachtiarz\Base\Interfaces\Http\ResponseInterface;
use TheBachtiarz\Base\Interfaces\Libraries\LoggerInterface;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http as CURL;
use Throwable;

abstract class AbstractCurl
{
    protected const string METHOD_GET = 'get';
    protected const string METHOD_POST = 'post';

    /**
     * Result response from CURL
     */
    private CurlResponseDTO $response;

    /**
     * Curl request method
     */
    private ?string $method = null;

    /**
     * Enable curl log
     */
    private bool $enableCurlLog = false;

    /**
     * @param LoggerInterface $logger
     * @param string|null $token
     * @param array|null $headers
     * @param array|null $body
     */
    public function __construct(
        protected LoggerInterface $logger,
        private ?string $token = null,
        private ?array $headers = null,
        private ?array $body = null,
    ) {
        $this->response = new CurlResponseDTO();
        $this->headers ??= [];
        $this->body ??= [];
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->response = new CurlResponseDTO();
    }

    // ? Public Methods

    /**
     * Process curl
     */
    public function process(): CurlResponseDTO
    {
        return $this->execute()->getResult();
    }

    /**
     * Enable curl log
     */
    public function enableCurlLog(): static
    {
        $this->enableCurlLog = true;

        return $this;
    }

    // ? Protected Methods

    /**
     * Execute curl process
     */
    abstract protected function execute(): static;

    /**
     * Resolve url domain location
     */
    abstract protected function urlResolver(): string;

    /**
     * Request curl init
     */
    protected function curl(): PendingRequest
    {
        $headers = ['Accept' => 'application/json'];

        if (count($this->headers)) {
            $headers = array_merge($headers, $this->headers);
        }

        return CURL::withHeaders($headers);
    }

    /**
     * Send request with method: GET
     */
    protected function get(): static
    {
        return $this->sendRequest(self::METHOD_GET);
    }

    /**
     * Send request with method: POST
     */
    protected function post(): static
    {
        return $this->sendRequest(self::METHOD_POST);
    }

    /**
     * Request curl send
     */
    protected function sendRequest(string $method): static
    {
        $this->method = $method;

        $pendingRequest = $this->curl();

        if ($this->token) {
            $pendingRequest->withToken($this->token);
        }

        $response = $pendingRequest->{$method}($this->urlResolver(), $this->getBody());
        assert($response instanceof Response);

        if ($this->enableCurlLog) {
            $this->writeCurlLog(json_encode([
                'request' => [
                    'method' => $this->method,
                    'headers' => $this->headers,
                    'body' => $this->body,
                ],
                'response' => json_validate(json: $response->json())
                    ? json_decode(json: $response->json(), associative: true)
                    : [],
            ]));
        }

        $this->processResponseResult($response);

        return $this;
    }

    /**
     * Get response result
     */
    protected function getResult(): CurlResponseDTO
    {
        return $this->response;
    }

    protected function processResponseResult(Response $response): void
    {
        try {
            $this->checkResponseUnprocessable(response: $response);
            $this->checkResponseStatus(response: $response);
            $this->setResponseData(response: $response);
        } catch (Throwable $th) {
            $this->logger->writeLog($th, 'curl');
        }
    }

    /**
     * Write log curl
     */
    protected function writeCurlLog(string $message): void
    {
        $this->logger->writeLog($message, 'curl');
    }

    /**
     * Check is response are unprocessable
     */
    protected function checkResponseUnprocessable(Response $response): void
    {
        $response = $response->json();

        if (
            in_array('errors', array_keys($response)) ||
            @$response[ResponseInterface::HTTP_CODE] === 422
        ) {
            $this->response = new CurlResponseDTO(
                httpCode: ResponseHttpCodeEnum::UNPROCESSABLE->value,
                status: ResponseStatusEnum::ERROR->value,
                message: $response[ResponseInterface::MESSAGE],
                data: @$response['errors'],
            );

            throw new Exception(message: $response[ResponseInterface::MESSAGE], code: 422);
        }
    }

    /**
     * Check response status
     */
    protected function checkResponseStatus(Response $response): void
    {
        $response = $response->json();

        if (!@$response[ResponseInterface::STATUS]) {
            throw new Exception(message: $response[ResponseInterface::MESSAGE], code: 202);
        }

        if ($response[ResponseInterface::STATUS] !== ResponseStatusEnum::SUCCESS->value) {
            throw new Exception(message: $response[ResponseInterface::MESSAGE], code: 202);
        }
    }

    /**
     * Set response data
     */
    protected function setResponseData(Response $response): void
    {
        $response = $response->json();

        $this->response = new CurlResponseDTO(
            httpCode: $response[ResponseInterface::HTTP_CODE],
            status: $response[ResponseInterface::STATUS],
            message: $response[ResponseInterface::MESSAGE],
            data: $response[ResponseInterface::DATA],
        );
    }

    // ? Private Methods

    // ? Getter Modules

    /**
     * Get the value of token
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Get the value of headers
     */
    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    /**
     * Get the value of body
     */
    public function getBody(): ?array
    {
        return $this->body;
    }

    // ? Setter Modules

    /**
     * Set the value of token
     */
    public function setToken(?string $token = null): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Set the value of headers
     */
    public function setHeaders(?array $headers = null): static
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set the value of body
     */
    public function setBody(?array $body = null): static
    {
        $this->body = $body;

        return $this;
    }
}
