<?php

namespace CQRS\Product\Domain\Event;

use CQRS\Common\Domain\Event\AggregateChanged;
use CQRS\Product\Application\ValueObject\ProductId;

class ProductNameWasChanged extends AggregateChanged
{
    public function __construct(
        private readonly ProductId $productId,
        private readonly string $name
    ) {
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->productId->toString(),
            'name' => $this->name,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            ProductId::fromString($payload['id']),
            $payload['name'],
        );
    }
}