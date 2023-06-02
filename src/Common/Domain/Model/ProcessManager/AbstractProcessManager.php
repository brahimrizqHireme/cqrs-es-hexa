<?php

namespace CQRS\Common\Domain\Model\ProcessManager;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;

abstract class AbstractProcessManager implements MessageConsumer
{
    public function handle(Message $message): void
    {
        $event = $message->payload();
        $className = get_class($event);
        $this->{'on' . substr($className, (strrpos($className, '\\') ?: -1) + 1)}($event);
    }
}