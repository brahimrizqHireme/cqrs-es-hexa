<?php

namespace CQRS\Common\Domain\Contract\Persistence;

use CQRS\Common\Domain\Contract\Persistence\Result\DeleteResultInterface;

interface PersistenceRemoveInterface
{
    public function remove(array $criteria = [], array $options = []): DeleteResultInterface;

}