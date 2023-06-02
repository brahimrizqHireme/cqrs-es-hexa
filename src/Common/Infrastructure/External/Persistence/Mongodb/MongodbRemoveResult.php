<?php

namespace CQRS\Common\Infrastructure\External\Persistence\Mongodb;

use CQRS\Common\Domain\Contract\Persistence\Result\DeleteResultInterface;
use MongoDB\DeleteResult;

readonly class MongodbRemoveResult implements DeleteResultInterface
{
    public function __construct(private DeleteResult $result)
    {
    }

    public function deleted(): bool {
        return $this->result->isAcknowledged();
    }
    public function count(): int {
        return $this->result->getDeletedCount();
    }
}