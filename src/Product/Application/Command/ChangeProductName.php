<?php

namespace CQRS\Product\Application\Command;

use CQRS\Common\Domain\Contract\Command\CommandInterface;
use CQRS\Common\Domain\Contract\Process\SyncProcessInterface;
use CQRS\Product\Application\ValueObject\ProductId;

final readonly class ChangeProductName implements CommandInterface, SyncProcessInterface
{
    public function __construct(
        private ProductId $id,
        private string $name
    )
    {
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}