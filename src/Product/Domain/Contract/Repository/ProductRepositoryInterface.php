<?php

namespace CQRS\Product\Domain\Contract\Repository;

use CQRS\Product\Application\ValueObject\ProductId;
use CQRS\Product\Domain\Model\Aggregate\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function get(ProductId $productId): Product;

}