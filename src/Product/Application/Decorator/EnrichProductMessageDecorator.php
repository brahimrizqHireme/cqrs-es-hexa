<?php

namespace CQRS\Product\Application\Decorator;

use CQRS\Common\Domain\Contract\Decorator\EventDecoratorInterface;
use EventSauce\EventSourcing\Message;

class EnrichProductMessageDecorator implements EventDecoratorInterface
{
    public function decorate(Message $message): Message
    {
        return $message->withHeader('x-decorated-by', 'xxxxxxxxxxxxxx decorator');
    }
}