<?php

namespace CQRS\Product\Domain\Model\Finder;

use CQRS\Common\Domain\Enum\Collections;
use CQRS\Common\Domain\Trait\MongoDbTrait;
use CQRS\Common\Infrastructure\External\Database\MongodbClient;

class ProductMongodbFinderInterface implements ProductFinderInterface
{
    use MongoDbTrait;

    public function __construct(protected MongodbClient $mongoClient)
    {
        $this->mainCollection = $this->getCollection(Collections::PRODUCT_COLLECTION->value);
    }
}