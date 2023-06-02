<?php

namespace CQRS\Common\Infrastructure\External\Persistence\Mongodb;

use CQRS\Common\Domain\Contract\Persistence\Result\FindOneResultInterface;

readonly class MongodbFindOneResult implements FindOneResultInterface
{
    public function __construct(private ?array $result)
    {
    }

    public function results(): array {
        return $this->result;
    }
}