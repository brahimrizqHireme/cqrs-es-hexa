<?php

namespace CQRS\DataFixture;

use CQRS\Common\Domain\Contract\Command\CommandBusInterface;
use CQRS\Product\Application\Command\CreateProductCommand;
use CQRS\Product\Application\ValueObject\ProductId;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ProductFixture implements DemoDataFixtureInterface
{
    private OutputInterface $output;
    public function __construct(
        private readonly CommandBusInterface $commandBus
    )
    {
        $this->output = new ConsoleOutput();
    }

    public function load(): void
    {
        for ($i = 0; $i< 10; $i++) {
            $this->commandBus->dispatch(new CreateProductCommand([
                'id' => $productId = ProductId::generate()->toString(),
                'name' => 'name' . $i,
                'description' => 'desc'.$i
            ]));
            $this->output->writeln(sprintf('<fg=yellow> Product ( %s ) was created.</>', $productId));
        }
    }
}