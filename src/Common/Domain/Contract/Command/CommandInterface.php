<?php
declare(strict_types=1);

namespace CQRS\Common\Domain\Contract\Command;

interface CommandInterface
{
    public function setPayload(array $payload): void;
    public function getPayload(): array;
}