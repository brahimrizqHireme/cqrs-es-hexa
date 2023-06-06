<?php

namespace CQRS\Product\Application\Command;

use CQRS\Common\Domain\Command\Command;
use CQRS\Common\Domain\Contract\Process\SyncProcessInterface;
use CQRS\Common\Domain\Trait\CommandPayloadTrait;
use CQRS\Product\Application\ValueObject\ProductId;

final class ChangeProductName extends Command implements SyncProcessInterface
{
    use CommandPayloadTrait;

    public static function withData(
        ... $params
    ): ChangeProductName {
        return new ChangeProductName([
            'id' => $params['id'],
            'name' => $params['name']
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