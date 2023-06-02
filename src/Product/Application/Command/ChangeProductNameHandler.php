<?php

namespace CQRS\Product\Application\Command;


use CQRS\Common\Domain\Contract\Command\CommandHandlerInterface;
use CQRS\Product\Domain\Contract\Repository\ProductRepositoryInterface;
use CQRS\Product\Domain\Exception\ProductExceptions;

final readonly class ChangeProductNameHandler implements CommandHandlerInterface
{

    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    /** @throws ProductExceptions **/
    public function __invoke(ChangeProductName $command): void
    {
        if (!$product = $this->productRepository->get($command->id())) {
            throw ProductExceptions::notFound();
        }

        $product->changeProductName($command->name());
        $this->productRepository->save($product);
    }
}