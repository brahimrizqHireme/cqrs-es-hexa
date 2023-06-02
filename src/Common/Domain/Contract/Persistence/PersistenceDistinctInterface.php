<?php

namespace CQRS\Common\Domain\Contract\Persistence;

use CQRS\Common\Domain\Contract\Persistence\Result\DistinctResultInterface;

interface PersistenceDistinctInterface
{
    public function distinct(string $fieldName, array $criteria = [], array $options = []): DistinctResultInterface;
}