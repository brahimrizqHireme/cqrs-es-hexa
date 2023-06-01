<?php

namespace CQRS\Common\Domain\Contract\Repository;

use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\Snapshotting\SnapshotRepository;

interface RepositoryInterface extends SnapshotRepository
{
}