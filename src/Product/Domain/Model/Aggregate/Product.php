<?php

namespace CQRS\Product\Domain\Model\Aggregate;

use CQRS\Common\Domain\Contract\Aggregate\UuidGenerator;
use CQRS\Common\Domain\Contract\Aggregate\AggregateRootInterface;
use CQRS\Common\Domain\Trait\BaseAggregateTrait;
use CQRS\Product\Application\ValueObject\ProductId;
use CQRS\Product\Domain\Model\Event\ProductNameWasChanged;
use CQRS\Product\Domain\Model\Event\ProductWasCreated;

final class Product implements AggregateRootInterface
{
    use BaseAggregateTrait;

    private ProductId $id;
    private string $name;
    private string $description;

    public function aggregateRootId(): ProductId
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
        $product->recordThat(
             ProductWasCreated::withData(
                $id,
                $name,
                $description
             )
        );

        return $product;
    }

    public function changeProductName(
        string $name
    ): void {
        $this->recordThat(
            ProductNameWasChanged::withData(
                $this->aggregateRootId(),
                $name
            )
        );
    }

    public function whenProductNameWasChanged(ProductNameWasChanged $event): void
    {
        $this->id = $event->getProductId();
        $this->name = $event->getName();
    }

    public function whenProductWasCreated(ProductWasCreated $event): void
    {
        $this->id = $event->getProductId();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
    }
}