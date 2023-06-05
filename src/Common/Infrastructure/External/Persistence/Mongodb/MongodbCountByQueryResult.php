<?php

namespace CQRS\Common\Infrastructure\External\Persistence\Mongodb;

use CQRS\Common\Domain\Contract\Persistence\Result\CountByQueryResultInterface;

readonly class MongodbCountByQueryResult implements CountByQueryResultInterface
{
    public function __construct(private int $total)
    {
    }

    public function count(): int {
        return $this->total;
    }
}