<?php

namespace CQRS\Common\Infrastructure\External\Persistence\Mongodb;

use CQRS\Common\Domain\Contract\Persistence\Result\InsertResultInterface;
use MongoDB\InsertOneResult;

readonly class MongodbInsertResult implements InsertResultInterface
{
    public function __construct(private InsertOneResult $result)
    {
    }

    public function inserted(): bool {
        return $this->result->isAcknowledged();
    }
}