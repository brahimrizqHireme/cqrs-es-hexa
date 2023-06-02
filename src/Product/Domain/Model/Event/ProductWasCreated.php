<?php

namespace CQRS\Product\Domain\Model\Event;

use CQRS\Common\Domain\Model\Event\AggregateChanged;
use CQRS\Product\Application\ValueObject\ProductId;

class ProductWasCreated extends AggregateChanged
{

    public static function withData(
        ProductId $productId,
        string    $name,
        string    $description
    ): ProductWasCreated
    {
        return self::occur(
            $productId,
            [
                'name' => $name,
                'description' => $description,
            ]
        );
    }

    public function getProductId(): ProductId
    {
        return ProductId::fromString($this->aggregateId());
    }

    public function getName(): string
    {
        return $this->payload['name'];
    }

    public function getDescription(): string
    {
        return $this->payload['description'];
    }
}