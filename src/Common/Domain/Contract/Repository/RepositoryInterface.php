<?php

namespace CQRS\Common\Domain\Contract\Repository;

use CQRS\Common\Domain\Contract\Aggregate\AggregateRootId;
use CQRS\Common\Domain\Contract\Aggregate\AggregateRootInterface;

interface RepositoryInterface
{
    public function save(AggregateRootInterface $aggregate);
    public function get(AggregateRootId $aggregateRootId) : AggregateRootInterface;
}