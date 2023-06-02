<?php

namespace CQRS\Product\Domain\Model\ProcessManager;

use CQRS\Common\Domain\Model\ProcessManager\AbstractProcessManager;
use CQRS\Product\Domain\Contract\ProductPersistenceInterface;
use CQRS\Product\Domain\Model\Event\ProductNameWasChanged;
use CQRS\Product\Domain\Model\Event\ProductWasCreated;

class ProductProcessManager extends AbstractProcessManager
{
    public function __construct(private readonly ProductPersistenceInterface $storageAdapter)
    {
    }

   public function onProductWasCreated(ProductWasCreated $event): void
    {
        $this->storageAdapter->insert([
            '_id' => $event->getProductId()->__toString(),
            'name' => $event->getName(),
            'description' => $event->getDescription(),
        ]);
    }

    public function onProductNameWasChanged(ProductNameWasChanged $event): void
    {
        $this->storageAdapter->update(
            ['_id' => $event->getProductId()->__toString()],
            [
                '$set' => [
                    'name' => $event->getName()
                ]
            ]
        );
    }
}