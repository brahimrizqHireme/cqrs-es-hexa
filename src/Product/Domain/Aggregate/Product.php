<?php

namespace CQRS\Product\Domain\Aggregate;

use CQRS\Common\Domain\Aggregate\Aggregate;
use CQRS\Common\Domain\Trait\BaseAggregateTrait;
use CQRS\Product\Application\ValueObject\ProductId;
use CQRS\Product\Domain\Event\ProductWasCreated;

class Product extends Aggregate
{
    use BaseAggregateTrait;

    private string $id;
    private string $name;
    private string $description;

    public static function createProduct(
        ProductId $id,
        string $name,
        string $description
    ): Product
    {
        $product = new static($id);
        $product->recordThat(new ProductWasCreated($id->toString(), $name, $description));
        return $product;
    }

    public function applyProductWasCreated(ProductWasCreated $event)
    {
        $this->id = $event->getId();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
    }

//    public function changeProductName(
//        string $name
//    ): void
//    {
//        $this->recordThat(
//            new ProductNameWasChanged(
//                $this->aggregateRootId->toString(),
//                $name
//            )
//        );
//    }
//
//    public function applyProductNameWasChanged(ProductNameWasChanged $event)
//    {
//        $this->id = $event->getAggregateRootId();
//        $this->name = $event->getName();
//    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


}