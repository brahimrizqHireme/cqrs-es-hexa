<?php

declare(strict_types=1);

namespace CQRS\Common\Application\Bus;

use CQRS\Common\Domain\Contract\Command\CommandBusInterface;
use CQRS\Common\Domain\Contract\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class MessengerCommandBus implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }
}