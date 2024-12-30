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
    /**
     * HTTP GET method
     */
    protected const string METHOD_GET = 'get';

    /**
     * HTTP HEAD method
     */
    protected const string METHOD_HEAD = 'head';

    /**
     * HTTP POST method
     */
    protected const string METHOD_POST = 'post';

    /**
     * HTTP PATCH method
     */
    protected const string METHOD_PATCH = 'patch';

    /**
     * HTTP PUT method
     */
    protected const string METHOD_PUT = 'put';

    /**
     * HTTP DELETE method
     */
    protected const string METHOD_DELETE = 'delete';

    /**
     * Result response from CURL
     * 
     * @var CurlResponseDTO
     */
    private CurlResponseDTO $response;

    /**
     * Curl request method
     * 
     * @var string|null
     */
    private ?string $method = null;

    /**
     * Enable curl log
     * 
     * @var bool
     */
    private bool $enableCurlLog = false;

    /**
     * Constructor
     * 
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

    /**
     * Process the CURL request
     * 
     * @return CurlResponseDTO
     */
    public function process(): CurlResponseDTO
    {
        return $this->execute()->getResult();
    }

    /**
     * Enable curl log
     * 
     * @return static
     */
    public function enableCurlLog(): static
    {
        $this->enableCurlLog = true;

        return $this;
    }

    /**
     * Execute the CURL request
     * 
     * @return static
     */
    abstract protected function execute(): static;

    /**
     * Resolve the URL for the CURL request
     * 
     * @return string
     */
    abstract protected function urlResolver(): string;

    /**
     * Initialize the CURL request
     * 
     * @return PendingRequest
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
     * Extend the CURL request
     * 
     * @param PendingRequest $curl
     * @return void
     */
    protected function curlExtend(PendingRequest &$curl): void {}

    /**
     * Send a GET request
     * 
     * @return static
     */
    protected function get(): static
    {
        return $this->sendRequest(self::METHOD_GET);
    }

    /**
     * Send a HEAD request
     * 
     * @return static
     */
    protected function head(): static
    {
        return $this->sendRequest(self::METHOD_HEAD);
    }

    /**
     * Send a POST request
     * 
     * @return static
     */
    protected function post(): static
    {
        return $this->sendRequest(self::METHOD_POST);
    }

    /**
     * Send a PATCH request
     * 
     * @return static
     */
    protected function patch(): static
    {
        return $this->sendRequest(self::METHOD_PATCH);
    }

    /**
     * Send a PUT request
     * 
     * @return static
     */
    protected function put(): static
    {
        return $this->sendRequest(self::METHOD_PUT);
    }

    /**
     * Send a DELETE request
     * 
     * @return static
     */
    protected function delete(): static
    {
        return $this->sendRequest(self::METHOD_DELETE);
    }

    /**
     * Send the CURL request
     * 
     * @param string $method
     * @return static
     */
    protected function sendRequest(string $method): static
    {
        $this->method = $method;
        $pendingRequest = $this->curl();

        if ($this->token) {
            $pendingRequest->withToken($this->token);
        }

        $this->curlExtend($pendingRequest);

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
     * Get the result of the CURL request
     * 
     * @return CurlResponseDTO
     */
    protected function getResult(): CurlResponseDTO
    {
        return $this->response;
    }

    /**
     * Process the response result
     * 
     * @param Response $response
     * @return void
     */
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
     * Write the CURL log
     * 
     * @param string $message
     * @return void
     */
    protected function writeCurlLog(string $message): void
    {
        $this->logger->writeLog($message, 'curl');
    }

    /**
     * Check if the response is unprocessable
     * 
     * @param Response $response
     * @return void
     * @throws Exception
     */
    protected function checkResponseUnprocessable(Response $response): void
    {
        $response = $response->json();

        if (
            in_array('errors', array_keys($response)) ||
            @$response[ResponseInterface::HTTP_CODE] === ResponseHttpCodeEnum::UNPROCESSABLE->value
        ) {
            $this->response = new CurlResponseDTO(
                httpCode: ResponseHttpCodeEnum::UNPROCESSABLE->value,
                status: ResponseStatusEnum::ERROR->value,
                message: $response[ResponseInterface::MESSAGE],
                data: @$response['errors'],
            );

            throw new Exception(message: $response[ResponseInterface::MESSAGE], code: ResponseHttpCodeEnum::UNPROCESSABLE->value);
        }
    }

    /**
     * Check the response status
     * 
     * @param Response $response
     * @return void
     * @throws Exception
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
     * Set the response data
     * 
     * @param Response $response
     * @return void
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

    /**
     * Get the token
     * 
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Get the headers
     * 
     * @return array|null
     */
    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    /**
     * Get the body
     * 
     * @return array|null
     */
    public function getBody(): ?array
    {
        return $this->body;
    }

    /**
     * Set the token
     * 
     * @param string|null $token
     * @return static
     */
    public function setToken(?string $token = null): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Set the headers
     * 
     * @param array|null $headers
     * @return static
     */
    public function setHeaders(?array $headers = []): static
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set the body
     * 
     * @param array|null $body
     * @return static
     */
    public function setBody(?array $body = []): static
    {
        $this->body = $body;

        return $this;
    }
}
