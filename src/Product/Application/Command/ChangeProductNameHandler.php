<?php

namespace CQRS\Product\Application\Command;


use CQRS\Common\Domain\Contract\Command\CommandHandlerInterface;
use CQRS\Product\Domain\Repository\ProductRepositoryInterface;

final readonly class ChangeProductNameHandler implements CommandHandlerInterface
{

    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    public function __invoke(ChangeProductName $command): void
    {
        //todo add check business
        $product = $this->productRepository->get($command->id());
        $product->changeProductName($command->name());
        $this->productRepository->save($product);
    }
}