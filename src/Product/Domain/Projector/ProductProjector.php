<?php

namespace CQRS\Product\Domain\Projector;

use CQRS\Common\Domain\Enum\Collections;
use CQRS\Common\Domain\Projector\Projector;
use CQRS\Common\Infrastructure\External\Database\MongodbClient;
use CQRS\Product\Domain\Event\ProductNameWasChanged;
use CQRS\Product\Domain\Event\ProductWasCreated;

class ProductProjector extends Projector
{
    public function __construct(protected MongodbClient $mongoClient)
    {
        $this->mainCollection = $this->getCollection(Collections::PRODUCT_COLLECTION->value);
    }

   public function onProductWasCreated(ProductWasCreated $event): void
    {
        $this->insert([
            '_id' => $event->productId()->toString(),
            'name' => $event->getName(),
            'description' => $event->getDescription(),
        ]);
    }

    public function onProductNameWasChanged(ProductNameWasChanged $event): void
    {
        $this->update(
            ['_id' => $event->productId()->toString()],
            [
                '$set' => [
                    'name' => $event->getName()
                ]
            ]
        );
    }
}