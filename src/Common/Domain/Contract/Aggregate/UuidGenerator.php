<?php

namespace CQRS\Common\Domain\Contract\Aggregate;

interface UuidGenerator
{
    public static function generate(): self;
    public static function isValid(string $id): bool;
    public function __toString(): string;
    public static function fromString(string $aggregateRootId): static;
    public function equals(UuidGenerator $other): bool;
}