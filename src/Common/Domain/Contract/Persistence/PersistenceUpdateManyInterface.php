<?php

namespace CQRS\Common\Domain\Contract\Persistence;

use CQRS\Common\Domain\Contract\Persistence\Result\UpdateManyResultInterface;

interface PersistenceUpdateManyInterface
{
    public function updateMany(array $criteria, array $set, array $options = []): UpdateManyResultInterface;
}