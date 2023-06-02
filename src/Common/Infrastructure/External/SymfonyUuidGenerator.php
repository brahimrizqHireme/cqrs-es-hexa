<?php

namespace CQRS\Common\Infrastructure\External;

use CQRS\Common\Domain\Contract\Aggregate\UuidGenerator;
use Symfony\Component\Uid\Uuid;

class SymfonyUuidGenerator extends Uuid implements UuidGenerator
{
    public static function generate(): UuidGenerator
    {
        return new static(Uuid::v4()->__toString());
    }
}