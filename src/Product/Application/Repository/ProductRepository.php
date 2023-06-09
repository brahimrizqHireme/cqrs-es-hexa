<?php

namespace CQRS\Product\Application\Repository;

use CQRS\Product\Application\ValueObject\ProductId;
use CQRS\Product\Domain\Contract\Repository\ProductRepositoryInterface;
use CQRS\Product\Domain\Exception\ProductNotFoundExceptions;
use CQRS\Product\Domain\Model\Aggregate\Product;
use EventSauce\EventSourcing\Snapshotting\AggregateRootRepositoryWithSnapshotting;
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
        try {
            /** @var Product $product */
            $product = $this->repository->retrieveFromSnapshot($productId);
        } catch (\Throwable $exception) {
            throw ProductNotFoundExceptions::notFound();
        }

        assert($product instanceof Product);
        return $product;
    }

}