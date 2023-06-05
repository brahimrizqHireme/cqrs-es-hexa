<?php

namespace CQRS\Common\Domain\Trait;

use CQRS\Common\Domain\Exception\DomainException;
use EventSauce\EventSourcing\AggregateRootBehaviourWithRequiredHistory;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\AggregateRootWithSnapshotting;
use EventSauce\EventSourcing\Snapshotting\SnapshottingBehaviour;
use Exception;

trait BaseAggregateTrait
{

    use SnapshottingBehaviour;
    use AggregateRootBehaviourWithRequiredHistory;

    private string $eventName;
    private int $aggregateRootVersion = 0;

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function setAggregateRootVersion(int $version): string
    {
        return $this->aggregateRootVersion = $version;
    }

    /**
     * @throws Exception
     */
    protected function apply(object $event): void
    {
        $this->eventName = get_class($event);
        $parts = explode('\\', get_class($event));
        $methodName = 'when' . end($parts);
        if (!method_exists($this, $methodName)) {
            throw DomainException::withApplyFunction(get_class($this), $methodName);
        }

        $this->{$methodName}($event);
        ++$this->aggregateRootVersion;
    }

    protected function createSnapshotState(): self
    {
        return $this;
    }

    protected static function reconstituteFromSnapshotState(AggregateRootId $id, $state): AggregateRootWithSnapshotting
    {
        return $state;
    }
}