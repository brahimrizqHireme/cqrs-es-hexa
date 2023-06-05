<?php

namespace CQRS\Common\Domain\Contract\Persistence;

use CQRS\Common\Domain\Contract\Persistence\Result\FindOneResultInterface;

interface PersistenceFindAndModifyInterface
{
    public function findAndModify(array $query, array $update = null, array $fields = null, array $options = []): FindOneResultInterface;

}