<?php

namespace CQRS\Common\Domain\Contract\Persistence;

use CQRS\Common\Domain\Contract\Persistence\Result\FindResultInterface;

interface PersistenceFindByQueryInterface
{
    public function find(array $query = [], array $fields = [], array $context = []): FindResultInterface;

}