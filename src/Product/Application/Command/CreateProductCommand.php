<?php

namespace CQRS\Product\Application\Command;

use CQRS\Common\Domain\Contract\Command\CommandInterface;
use CQRS\Common\Domain\Contract\Process\SyncProcessInterface;
use CQRS\Common\Domain\Trait\CommandPayloadTrait;
use CQRS\Product\Application\ValueObject\ProductId;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CreateProductCommand implements CommandInterface, SyncProcessInterface
{
    use CommandPayloadTrait;

    public function withData(
        string $id,
        string $name,
        string $description,
    ): CreateProductCommand {
        return new self([
            'id' => $id,
            'name' => $name,
            'description' => $description
        ]);
    }

    public function id(): ProductId
    {
        return ProductId::fromString($this->payload['id']);
    }

    public function getName(): string
    {
        return $this->payload['name'];
    }

    public function getDescription(): string
    {
        return $this->payload['description'];
    }
}