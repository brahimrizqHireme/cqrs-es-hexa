<?php

namespace CQRS\Product\Domain\Model\Event;

use CQRS\Common\Domain\Model\Event\AggregateChanged;
use CQRS\Product\Application\ValueObject\ProductId;

class ProductNameWasChanged extends AggregateChanged
{
    public static function withData(
        ProductId $productId,
        string    $name
    ): ProductNameWasChanged
    {
        return self::occur(
            $productId,
            [
                'name' => $name,
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
}