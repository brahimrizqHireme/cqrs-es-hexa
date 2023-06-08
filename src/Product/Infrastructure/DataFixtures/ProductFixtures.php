<?php

namespace CQRS\Product\Infrastructure\DataFixtures;

use CQRS\Common\Domain\Contract\Command\CommandBusInterface;
use CQRS\Product\Application\Command\CreateProductCommand;
use CQRS\Product\Application\ValueObject\ProductId;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    )
    {
    }
    public function load(ObjectManager $manager): void
    {
         for ($i = 0; $i< 10; $i++) {
             $this->commandBus->dispatch(new CreateProductCommand([
                 'id' => ProductId::generate()->toString(),
                 'name' => 'name' . $i,
                 'description' => 'desc'.$i
             ]));
         }
    }
}
