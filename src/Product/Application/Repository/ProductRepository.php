<?php

namespace CQRS\Product\Application\Repository;

use CQRS\Common\Domain\Contract\Repository\RepositoryInterface;
use CQRS\Product\Application\ValueObject\ProductId;
use CQRS\Product\Domain\Aggregate\Product;
use CQRS\Product\Domain\Repository\ProductRepositoryInterface;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\AggregateRootRepositoryWithSnapshotting;
use EventSauce\EventSourcing\Snapshotting\Snapshot;
use EventSauce\EventSourcing\Snapshotting\SnapshotRepository;
use function assert;

readonly class ProductRepository implements ProductRepositoryInterface
{

    public function __construct(private AggregateRootRepositoryWithSnapshotting $repository) {}

    public function save(Product $product): void
    {
        $this->repository->persist($product);
        $this->repository->storeSnapshot($product);
    }

    public function get(ProductId $productId): Product
    {
        /** @var Product $product */
        $product = $this->repository->retrieveFromSnapshot($productId);

        assert($product instanceof Product);
        return $product;
    }

}