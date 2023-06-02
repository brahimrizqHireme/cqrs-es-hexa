<?php

namespace CQRS\Common\Domain\Contract\Persistence;

use CQRS\Common\Domain\Contract\Persistence\Result\CountByQueryResultInterface;

interface PersistenceCountByQueryInterface
{
    public function count(array $query = [], array $options = []): CountByQueryResultInterface;
}