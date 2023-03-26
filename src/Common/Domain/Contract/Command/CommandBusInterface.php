<?php
declare(strict_types=1);

namespace CQRS\Common\Domain\Contract\Command;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}