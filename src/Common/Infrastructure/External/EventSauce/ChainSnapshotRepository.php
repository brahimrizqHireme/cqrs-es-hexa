<?php

namespace CQRS\Common\Infrastructure\External\EventSauce;


use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\Snapshot;
use EventSauce\EventSourcing\Snapshotting\SnapshotRepository;

class ChainSnapshotRepository implements SnapshotRepository
{
    /**
     * @var SnapshotRepository[]
     */
    private array $repositories;

    public function __construct(...$repositories)
    {
        $this->repositories = $repositories;
    }

    public function persist(Snapshot $snapshot): void
    {
        foreach (array_reverse($this->repositories) as $repository) {
            $repository->persist($snapshot);
        }
    }

    public function retrieve(AggregateRootId $id): ?Snapshot
    {
        foreach ($this->repositories as $repository) {
            $snapshot = $repository->retrieve($id);
            if (null !== $snapshot) {
                return $snapshot;
            }
        }

        return null;
    }
}