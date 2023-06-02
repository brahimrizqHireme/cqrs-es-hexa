<?php

namespace CQRS\Common\Domain\Contract\Persistence;

use CQRS\Common\Domain\Contract\Persistence\Result\DeleteResultInterface;

interface PersistenceRemoveManyInterface
{
    public function removeMany(array $criteria = [], array $options = []): DeleteResultInterface;

}