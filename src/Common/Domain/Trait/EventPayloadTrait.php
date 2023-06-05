<?php

namespace CQRS\Common\Domain\Trait;

use CQRS\Common\Domain\Contract\Aggregate\UuidGenerator;

trait EventPayloadTrait
{
    public function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    protected function setAggregateId(UuidGenerator $aggregateId): void
    {
        $this->metadata['aggregate_id'] = $aggregateId->__toString();
        $this->payload['id'] = $aggregateId->__toString();
    }

    public function aggregateId(): string
    {
        return $this->metadata['aggregate_id'];
    }
}