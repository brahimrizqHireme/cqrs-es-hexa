<?php

namespace CQRS\Common\Domain\Contract\Persistence;

use CQRS\Common\Domain\Contract\Persistence\Result\InsertManyResultInterface;

interface PersistenceInsertManyInterface
{
    public function insertMany(array $data, array $options = []): InsertManyResultInterface;
}