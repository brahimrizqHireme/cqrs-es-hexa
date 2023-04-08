<?php

namespace CQRS\Common\Domain\Trait;

use EventSauce\EventSourcing\AggregateRootBehaviour;

trait BaseAggregateTrait
{
    use AggregateRootBehaviour;
}