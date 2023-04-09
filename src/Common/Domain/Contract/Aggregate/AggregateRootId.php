<?php

namespace CQRS\Common\Domain\Contract\Aggregate;

use EventSauce\EventSourcing\AggregateRootId as BaseAggregateRootId;

interface AggregateRootId extends BaseAggregateRootId
{
    public static function generate() : self;
    public static function fromString(string $aggregateRootId): static;

}