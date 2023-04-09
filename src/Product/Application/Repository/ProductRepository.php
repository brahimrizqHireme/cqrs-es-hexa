<?php

namespace CQRS\Product\Application\Repository;

use CQRS\Common\Domain\Contract\Repository\RepositoryInterface;
use CQRS\Product\Application\ValueObject\ProductId;
use CQRS\Product\Domain\Aggregate\Product;
use CQRS\Product\Domain\Repository\ProductRepositoryInterface;
use function assert;

readonly class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(private RepositoryInterface $repository){}

    public function save(Product $product): void
    {
        $this->repository->persist($product);
    }

    public function get(ProductId $productId): Product
    {
        $tab = $this->repository->retrieve($productId);
        assert($tab instanceof Product);

        return $tab;
    }
}