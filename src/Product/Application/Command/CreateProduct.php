<?php

namespace CQRS\Product\Application\Command;

use CQRS\Common\Domain\Contract\Command\CommandInterface;
use CQRS\Common\Domain\Contract\Process\SyncProcessInterface;
use CQRS\Product\Application\ValueObject\ProductId;

final readonly class CreateProduct implements CommandInterface, SyncProcessInterface
{
    public function __construct(
        private ProductId $id,
        private string $name,
        private string $description,
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

    public function description(): string
    {
        return $this->description;
    }
}