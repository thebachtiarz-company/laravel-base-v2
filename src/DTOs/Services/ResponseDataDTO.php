<?php

namespace TheBachtiarz\Base\DTOs\Services;

use Illuminate\Contracts\Support\Arrayable;
use TheBachtiarz\Base\Enums\Services\ResponseConditionEnum;
use TheBachtiarz\Base\Enums\Services\ResponseHttpCodeEnum;
use TheBachtiarz\Base\Enums\Services\ResponseStatusEnum;
use TheBachtiarz\Base\Interfaces\Http\ResponseInterface;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use TheBachtiarz\Base\Models\AbstractModel;

class ResponseDataDTO implements Arrayable
{
    /**
     * @template TValue
     *
     * @param ResponseConditionEnum $condition
     * @param ResponseStatusEnum $status
     * @param ResponseHttpCodeEnum $httpCode
     * @param string $message
     * @param TValue $data
     * @param ModelInterface|AbstractModel|null $model
     */
    public function __construct(
        public ResponseConditionEnum $condition = ResponseConditionEnum::FALSE,
        public ResponseStatusEnum $status = ResponseStatusEnum::ERROR,
        public ResponseHttpCodeEnum $httpCode = ResponseHttpCodeEnum::ACCEPTED,
        public string $message = '',
        public mixed $data = [],
        public ModelInterface|AbstractModel|null $model = null,
    ) {}

    public function toArray(): array
    {
        return [
            ResponseInterface::CONDITION => $this->condition->toBoolean(),
            ResponseInterface::STATUS => $this->status->value,
            ResponseInterface::HTTP_CODE => $this->httpCode->value,
            ResponseInterface::MESSAGE => $this->message,
            ResponseInterface::DATA => $this->data,
        ];
    }
}
