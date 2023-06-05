<?php

namespace CQRS\Common\Application\Decorator;

use CQRS\Common\Domain\Contract\Decorator\EventDecoratorInterface;
use CQRS\Common\Domain\Helper\CommonService;
use EventSauce\EventSourcing\Message;

class EnrichRequestHeadersMessageDecorator implements EventDecoratorInterface
{
    public function decorate(Message $message): Message
    {
        return $message->withHeaders(CommonService::getClientDataFromServer());
    }
}