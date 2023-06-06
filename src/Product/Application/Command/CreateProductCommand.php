<?php

namespace CQRS\Product\Application\Command;

use CQRS\Common\Domain\Command\Command;
use CQRS\Common\Domain\Contract\Process\SyncProcessInterface;
use CQRS\Product\Application\ValueObject\ProductId;

final class CreateProductCommand extends Command implements SyncProcessInterface
{
    public static function withData(
        ... $params
    ): CreateProductCommand {
        return new self([
            'id' => $params['id'],
            'name' => $params['name'],
            'description' => $params['description']
        ]);
    }

    public function getId(): ProductId
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