<?php

namespace CQRS\Product\Application\Decorator;

use CQRS\Common\Application\Service\CommonService;
use CQRS\Common\Domain\Contract\Decorator\EventDecoratorInterface;
use EventSauce\EventSourcing\Message;

class EnrichRequestHeadersMessageDecorator implements EventDecoratorInterface
{
    public function decorate(Message $message): Message
    {
        return $message->withHeaders(CommonService::getClientDataFromServer());
    }
}