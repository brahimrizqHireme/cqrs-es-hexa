<?php

namespace CQRS\Common\Infrastructure\External\Persistence\Mongodb;

use CQRS\Common\Domain\Contract\Persistence\Result\DistinctResultInterface;

readonly class MongodbDistinctResult implements DistinctResultInterface
{
    public function __construct(private array $result)
    {
    }

    public function results(): array {
        return $this->result;
    }
}