<?php

namespace CQRS\Common\Infrastructure\External\Persistence\Mongodb;

use CQRS\Common\Domain\Contract\Persistence\Result\FindResultInterface;
use MongoDB\Driver\CursorInterface;

readonly class MongodbFindAllFindResult implements FindResultInterface
{
    public function __construct(private CursorInterface $result)
    {
    }

    public function results(): array {
        return $this->result->toArray();
    }
}