<?php

namespace CQRS\Common\Domain\Contract\Persistence;

use CQRS\Common\Domain\Contract\Persistence\Result\InsertResultInterface;

interface PersistenceInsertInterface
{
    public function insert(array $data, array $options = []): InsertResultInterface;
}