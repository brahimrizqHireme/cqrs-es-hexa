<?php

namespace CQRS\Common\Application\Bus;

use CQRS\Common\Domain\Contract\Query\QueryBusInterface;
use CQRS\Common\Domain\Contract\Query\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait {
        handle as handleQuery;
    }

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function handle(QueryInterface $message): mixed
    {
        return $this->handleQuery($message);
    }
}