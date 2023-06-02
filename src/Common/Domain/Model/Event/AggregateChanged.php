<?php

namespace CQRS\Common\Domain\Model\Event;

use CQRS\Common\Domain\Contract\Aggregate\UuidGenerator;
use CQRS\Common\Domain\Contract\Event\AggregateEventInterface;
use CQRS\Common\Domain\Trait\EventPayloadTrait;
use CQRS\Product\Application\ValueObject\ProductId;

abstract class AggregateChanged implements AggregateEventInterface
{
    use EventPayloadTrait;

    public function __construct(
        protected UuidGenerator $aggregateId,
        public array            $payload,
        protected array         $metadata = []
    ) {
        $this->setPayload($payload);
        $this->setAggregateId($aggregateId);
    }

    public static function occur(UuidGenerator $aggregateId, array $payload = []): static
    {
        return new static($aggregateId, $payload);
    }

    public function toPayload(): array
    {
        return $this->getPayload();
    }

    public static function fromPayload(array $payload): static
    {
        return new static(ProductId::fromString($payload['id']), $payload);
    }
}