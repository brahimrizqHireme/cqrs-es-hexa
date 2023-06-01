<?php

namespace CQRS\Product\Application\Command;

use CQRS\Common\Domain\Contract\Command\CommandInterface;
use CQRS\Common\Domain\Contract\Process\SyncProcessInterface;
use CQRS\Product\Application\ValueObject\ProductId;
use Symfony\Component\Validator\Constraints\NotBlank;

final  class CreateProduct implements CommandInterface, SyncProcessInterface
{
    public function __construct(
        private readonly ProductId $id,
        private readonly string    $name,
        private readonly string    $description,
    )
    {
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    #[NotBlank]
    public function getName(): string
    {
        return $this->name;
    }

    #[NotBlank]
    public function getDescription(): string
    {
        return $this->description;
    }
}