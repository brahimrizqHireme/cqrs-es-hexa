<?php

namespace CQRS\Product\Domain\Event;

use CQRS\Common\Domain\Event\AggregateChanged;
use CQRS\Product\Application\ValueObject\ProductId;

class ProductWasCreated extends AggregateChanged
{
    public function __construct(
        private ProductId       $id,
        private readonly string $name,
        private readonly string $description
    )
    {
    }

    public function productId(): ProductId
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

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            ProductId::fromString($payload['id']),
            $payload['name'],
            $payload['description'],
        );
    }
}