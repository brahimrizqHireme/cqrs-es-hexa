<?php
declare(strict_types=1);

namespace CQRS\Common\Domain\Helper;

use CQRS\Common\Domain\Contract\Aggregate\UuidGenerator;
use CQRS\Common\Domain\Exception\InvalidArgumentException;
use CQRS\Common\Infrastructure\External\SymfonyUuidGenerator;
use EventSauce\EventSourcing\AggregateRootId;

abstract class AbstractUuidGenerator implements UuidGenerator, \Stringable, AggregateRootId
{
    private string $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function generate(): self
    {
        return new static(SymfonyUuidGenerator::generate()->__toString());
    }

    public static function isValid(string $id): bool
    {
        return SymfonyUuidGenerator::isValid($id);
    }

    public function equals(UuidGenerator $other): bool
    {
        return $this->__toString() === $other->__toString();
    }

    public static function fromString(string $aggregateRootId): static
    {
        return new static($aggregateRootId);
    }

    private function ensureIsValidUuid(string $id): void
    {
        if (!static::isValid($id)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $id));
        }
    }
}