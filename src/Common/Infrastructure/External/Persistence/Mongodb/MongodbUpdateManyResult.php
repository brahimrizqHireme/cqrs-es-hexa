<?php

namespace CQRS\Common\Infrastructure\External\Persistence\Mongodb;

use CQRS\Common\Domain\Contract\Persistence\Result\UpdateManyResultInterface;
use MongoDB\UpdateResult;

readonly class MongodbUpdateManyResult implements UpdateManyResultInterface
{
    public function __construct(private UpdateResult $result)
    {
    }

    public function updated(): bool {
        return $this->result->isAcknowledged();
    }

    public function count(): bool
    {
        return $this->result->getModifiedCount();
    }
}