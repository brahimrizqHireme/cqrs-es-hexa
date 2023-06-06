<?php

namespace CQRS\Product\Application\Command;


use CQRS\Common\Domain\Contract\Command\CommandHandlerInterface;
use CQRS\Product\Domain\Contract\Repository\ProductRepositoryInterface;
use CQRS\Product\Domain\Model\Aggregate\Product;

final  readonly class CreateProductCommandHandler implements CommandHandlerInterface
{

    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    public function __invoke(CreateProductCommand $command): void
    {
        //todo add check business
        $product = Product::createProduct(
            $command->getId(),
            $command->getName(),
            $command->getDescription()
        );

        $this->productRepository->save($product);
    }
}