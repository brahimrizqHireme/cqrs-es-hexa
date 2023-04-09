<?php

namespace CQRS\Product\Application\Command;


use CQRS\Common\Domain\Contract\Command\CommandHandlerInterface;
use CQRS\Product\Domain\Aggregate\Product;
use CQRS\Product\Domain\Repository\ProductRepositoryInterface;

final readonly class CreateProductHandler implements CommandHandlerInterface
{

    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    public function __invoke(CreateProduct $command): void
    {
        //todo add check business
        $product = Product::createProduct(
            $command->id(),
            $command->name(),
            $command->description()
        );

        $this->productRepository->save($product);
    }
}