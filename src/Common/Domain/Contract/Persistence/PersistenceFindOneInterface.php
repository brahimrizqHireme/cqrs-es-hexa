<?php

namespace CQRS\Common\Domain\Contract\Persistence;

use CQRS\Common\Domain\Contract\Persistence\Result\FindOneResultInterface;

interface PersistenceFindOneInterface
{
    public function findOne(array $query = [], array $fields = [], array $options = []): FindOneResultInterface;

}