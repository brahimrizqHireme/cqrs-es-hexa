<?php

namespace CQRS\Common\Infrastructure\External\Persistence\Mongodb;

use CQRS\Common\Domain\Contract\Persistence\Result\InsertManyResultInterface;
use MongoDB\InsertManyResult;

readonly class MongodbInsertManyResult implements InsertManyResultInterface
{
    public function __construct(private InsertManyResult $result)
    {
    }

    public function inserted(): bool {
        return $this->result->isAcknowledged();
    }

    public function count(): int
    {
       return $this->result->getInsertedCount();
    }
}