<?php

namespace CQRS\Product\Application\ValueObject;

use CQRS\Common\Domain\Contract\Aggregate\AggregateRootId;
use CQRS\Common\Infrastructure\External\Impl\UuidImplement;

class ProductId implements AggregateRootId
{
    private $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function generate(): self
    {
        return new self(UuidImplement::v4()->__toString());
    }

    public static function fromString(string $aggregateRootId): static
    {
        return new self($aggregateRootId);
    }
}