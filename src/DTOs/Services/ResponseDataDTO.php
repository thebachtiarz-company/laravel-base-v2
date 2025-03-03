<?php

namespace TheBachtiarz\Base\DTOs\Services;

use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\Base\DTOs\AbstractDTO;
use TheBachtiarz\Base\Enums\Services\ResponseConditionEnum;
use TheBachtiarz\Base\Enums\Services\ResponseHttpCodeEnum;
use TheBachtiarz\Base\Enums\Services\ResponseStatusEnum;
use TheBachtiarz\Base\Interfaces\Http\ResponseInterface;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use TheBachtiarz\Base\Models\AbstractModel;

class ResponseDataDTO extends AbstractDTO
{
    /**
     * @template TValue
     *
     * @param ResponseConditionEnum $condition
     * @param ResponseStatusEnum $status
     * @param ResponseHttpCodeEnum $httpCode
     * @param string $message
     * @param ModelInterface|AbstractModel|Model|null $model
     * @param TValue $data
     */
    public function __construct(
        public ResponseConditionEnum $condition = ResponseConditionEnum::FALSE,
        public ResponseStatusEnum $status = ResponseStatusEnum::ERROR,
        public ResponseHttpCodeEnum $httpCode = ResponseHttpCodeEnum::ACCEPTED,
        public string $message = '',
        public ModelInterface|AbstractModel|Model|null $model = null,
        public mixed $data = [],
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

    public function fromArray(array $data): static
    {
        $this->{ResponseInterface::CONDITION} ??= ResponseConditionEnum::tryFrom(@$data[ResponseInterface::CONDITION]) ?? ResponseConditionEnum::FALSE;
        $this->{ResponseInterface::STATUS} ??= ResponseStatusEnum::tryFrom(@$data[ResponseInterface::STATUS]) ?? ResponseStatusEnum::ERROR;
        $this->{ResponseInterface::HTTP_CODE} ??= ResponseHttpCodeEnum::tryFrom(@$data[ResponseInterface::HTTP_CODE]) ?? ResponseHttpCodeEnum::ACCEPTED;
        $this->{ResponseInterface::MESSAGE} = @$data[ResponseInterface::MESSAGE] ?? '';
        $this->{ResponseInterface::DATA} = @$data[ResponseInterface::DATA] ?? [];

        return $this;
    }
}
