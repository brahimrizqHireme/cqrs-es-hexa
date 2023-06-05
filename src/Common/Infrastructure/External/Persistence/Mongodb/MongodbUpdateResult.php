<?php

namespace CQRS\Common\Infrastructure\External\Persistence\Mongodb;

use CQRS\Common\Domain\Contract\Persistence\Result\UpdateResultInterface;
use MongoDB\UpdateResult;

readonly class MongodbUpdateResult implements UpdateResultInterface
{
    public function __construct(private UpdateResult $result)
    {
    }

    public function updated(): bool {
        return $this->result->isAcknowledged();
    }
}