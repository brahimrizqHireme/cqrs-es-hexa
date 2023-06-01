<?php

namespace CQRS\Common\Domain\Projector;

use CQRS\Common\Domain\Contract\Projector\ProjectorInterface;
use CQRS\Common\Domain\Trait\MongoDbTrait;
use EventSauce\EventSourcing\Message;

abstract class Projector implements ProjectorInterface
{
    use MongoDbTrait;
    public function handle(Message $message): void
    {
        $event = $message->payload();
        $className = get_class($event);
        $this->{'on' . substr($className, (strrpos($className, '\\') ?: -1) + 1)}($event);
    }
}