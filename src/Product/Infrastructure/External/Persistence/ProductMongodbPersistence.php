<?php

namespace CQRS\Product\Infrastructure\External\Persistence;

use CQRS\Common\Domain\Enum\Collections;
use CQRS\Common\Domain\Trait\MongoDbTrait;
use CQRS\Common\Infrastructure\External\Database\MongodbClient;
use CQRS\Product\Domain\Contract\ProductPersistenceInterface;

class ProductMongodbPersistence implements ProductPersistenceInterface
{
    use MongoDbTrait;

    public function __construct(protected MongodbClient $mongoClient)
    {
        $this->mainCollection = $this->getCollection(Collections::PRODUCT_COLLECTION->value);
    }
}