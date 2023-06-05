<?php

namespace CQRS\Common\Domain\Contract\Aggregate;

use EventSauce\EventSourcing\Snapshotting\AggregateRootWithSnapshotting;

interface AggregateRootInterface extends AggregateRootWithSnapshotting
{

}