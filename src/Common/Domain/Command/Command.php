<?php

namespace CQRS\Common\Domain\Command;

use CQRS\Common\Domain\Contract\Command\CommandInterface;
use CQRS\Common\Domain\Trait\CommandPayloadTrait;

abstract class Command implements CommandInterface
{
    use CommandPayloadTrait;
    abstract public static function withData(... $params): CommandInterface;
}