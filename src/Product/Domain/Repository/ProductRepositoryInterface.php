<?php

namespace CQRS\Product\Domain\Repository;

use CQRS\Product\Application\ValueObject\ProductId;
use CQRS\Product\Domain\Aggregate\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function get(ProductId $productId): Product;

}