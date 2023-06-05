<?php

namespace CQRS\Common\Domain\Contract\Persistence;

use CQRS\Common\Domain\Contract\Persistence\Result\FindResultInterface;

interface PersistenceFindAllInterface
{
    public function findAll(): FindResultInterface;

}