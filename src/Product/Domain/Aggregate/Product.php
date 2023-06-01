<?php

namespace CQRS\Product\Domain\Aggregate;

use CQRS\Common\Domain\Aggregate\Aggregate;
use CQRS\Common\Domain\Trait\BaseAggregateTrait;
use CQRS\Product\Application\ValueObject\ProductId;
use CQRS\Product\Domain\Event\ProductNameWasChanged;
use CQRS\Product\Domain\Event\ProductWasCreated;
use EventSauce\EventSourcing\AggregateAlwaysAppliesEvents;
use EventSauce\EventSourcing\AggregateRootBehaviourWithRequiredHistory;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\AggregateRootWithSnapshotting;
use EventSauce\EventSourcing\Snapshotting\Snapshot;
use EventSauce\EventSourcing\Snapshotting\SnapshottingBehaviour;
use Generator;

final class Product implements AggregateRootWithSnapshotting
{
    use BaseAggregateTrait;

    private ProductId $id;
    private string $name;
    private string $description;

    public function aggregateRootId(): AggregateRootId
    {
        return $this->id;
    }

    public function getId() :ProductId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setId(ProductId $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public static function createProduct(
        ProductId $id,
        string $name,
        string $description
    ): Product
    {
        $product = new Product($id);
        $product->recordThat(new ProductWasCreated($id, $name, $description));
        return $product;
    }

    public function changeProductName(
        string $name
    ): void {
        $this->recordThat(
            new ProductNameWasChanged(
                $this->aggregateRootId(),
                $name
            )
        );
    }

    public function whenProductNameWasChanged(ProductNameWasChanged $event): void
    {
        $this->id = $event->productId();
        $this->name = $event->getName();
    }

    public function whenProductWasCreated(ProductWasCreated $event): void
    {
        $this->id = $event->productId();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
    }
}