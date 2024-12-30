<?php

namespace TheBachtiarz\Base\Libraries\Log;

use TheBachtiarz\Base\Interfaces\Libraries\LoggerInterface;
use TheBachtiarz\Base\Libraries\Log\Entities\AbstractLogEntity;
use TheBachtiarz\Base\Libraries\Log\Entities\Error;
use TheBachtiarz\Base\Libraries\Log\Entities\Info;
use Throwable;

class LoggerService implements LoggerInterface
{
    /**
     * Logger class entity
     *
     * @var AbstractLogEntity[]
     */
    private array $loggerClassEntity = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addClassEntity([
            Error::class,
            Info::class,
        ]);
    }

    /**
     * Write logger
     * 
     * @param mixed $entity
     * @param string|null $channel
     * @return void
     */
    public function writeLog(mixed $entity, ?string $channel = null): void
    {
        $logger = app()->make($this->defineEntityType($entity));
        assert($logger instanceof AbstractLogEntity);

        if ($channel && in_array($channel, array_keys(config('logging.channels')))) {
            $logger->setChannel($channel);
        }

        $logger->setLogEntity($entity)->process();
    }

    /**
     * Define log entity type
     * 
     * @param mixed $entity
     * @return string
     */
    protected function defineEntityType(mixed $entity): string
    {
        $result = Info::class;

        try {
            if ($entity instanceof Throwable) {
                $result = Error::class;
            }

            throw_if(!in_array($result, $this->loggerClassEntity), 'Exception', 'Class not found');
        } catch (\Throwable $th) {
            $result = Info::class;
        }

        return $result;
    }

    /**
     * Add class entities
     *
     * @param AbstractLogEntity[] $classEntities
     * @return self
     */
    protected function addClassEntity(array $classEntities = []): self
    {
        $this->loggerClassEntity = array_unique(
            array_merge(
                $this->loggerClassEntity,
                $classEntities,
            ),
        );

        return $this;
    }
}
