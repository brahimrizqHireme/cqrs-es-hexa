<?php

namespace CQRS\Common\Domain\Contract\Persistence;

use CQRS\Common\Domain\Contract\Persistence\Result\FindOneResultInterface;

interface PersistenceFindByIdInterface
{
    public function findById(string $id): FindOneResultInterface;
}