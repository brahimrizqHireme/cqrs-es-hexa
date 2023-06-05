<?php

namespace CQRS\Common\Domain\Contract\Persistence;

use CQRS\Common\Domain\Contract\Persistence\Result\UpdateResultInterface;

interface PersistenceUpdateInterface
{
    public function update(array $criteria, array $set, array $options = []): UpdateResultInterface;
}