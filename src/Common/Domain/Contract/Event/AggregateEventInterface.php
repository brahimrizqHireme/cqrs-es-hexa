<?php

namespace CQRS\Common\Domain\Contract\Event;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

interface AggregateEventInterface extends SerializablePayload
{

}