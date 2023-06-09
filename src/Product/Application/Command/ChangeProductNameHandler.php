<?php

namespace CQRS\Product\Application\Command;


use CQRS\Common\Domain\Contract\Command\CommandHandlerInterface;
use CQRS\Product\Domain\Contract\Repository\ProductRepositoryInterface;

final readonly class ChangeProductNameHandler implements CommandHandlerInterface
{

    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    public function __invoke(ChangeProductName $command): void
    {
        $product = $this->productRepository->get($command->id());
        $product->changeProductName($command->name());
        $this->productRepository->save($product);
    }
}