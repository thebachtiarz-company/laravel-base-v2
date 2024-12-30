<?php

namespace TheBachtiarz\Base\Http\Controllers;

use TheBachtiarz\Base\DTOs\Services\ResponseDataDTO;
use TheBachtiarz\Base\Helpers\Services\ResponseCollectionHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

abstract class AbstractController
{
    /**
     * Constructor
     */
    public function __construct() {}

    /**
     * Destructor
     */
    public function __destruct() {}

    // ? Public Methods

    // ? Protected Methods

    /**
     * Get response as JSON
     *
     * @param string|null $responseCode
     * @return JsonResponse
     */
    protected function getJsonResponse(?string $responseCode = null): JsonResponse
    {
        $result = $responseCode
            ? ResponseCollectionHelper::getResponses()->get($responseCode)
            : ResponseCollectionHelper::getLatestResponse();

        assert($result instanceof ResponseDataDTO);

        return (new JsonResponse())
            ->setStatusCode(code: $result->httpCode->value)
            ->setDate(Carbon::now())
            ->setData(data: $result->toArray());
    }

    // ? Private Methods

    // ? Getter Modules

    // ? Setter Modules
}
