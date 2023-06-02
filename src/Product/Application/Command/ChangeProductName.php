<?php

namespace CQRS\Product\Application\Command;

use CQRS\Common\Domain\Contract\Command\CommandInterface;
use CQRS\Common\Domain\Contract\Process\SyncProcessInterface;
use CQRS\Common\Domain\Trait\CommandPayloadTrait;
use CQRS\Product\Application\ValueObject\ProductId;

final class ChangeProductName implements CommandInterface, SyncProcessInterface
{
    use CommandPayloadTrait;

    public function withData(
        string $id,
        string $name
    ): ChangeProductName {
        return new ChangeProductName([
            'id' => $id,
            'name' => $name
        ]);
    }

    public function id(): ProductId
    {
        return ProductId::fromString($this->payload['id']);
    }

    public function name(): string
    {
        return $this->payload['name'];
    }
}