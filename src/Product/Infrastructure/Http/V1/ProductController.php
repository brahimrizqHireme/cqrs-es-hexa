<?php

namespace CQRS\Product\Infrastructure\Http\V1;

use CQRS\Common\Domain\Contract\Command\CommandBusInterface;
use CQRS\Common\Infrastructure\Http\BaseApiController;
use CQRS\Product\Application\Command\ChangeProductName;
use CQRS\Product\Application\Command\CreateProductCommand;
use CQRS\Product\Infrastructure\Request\CreateProductRequest;
use CQRS\Product\Infrastructure\Request\EditProductRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends BaseApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    )
    {
    }

    #[Route('/create', name: 'product_create', methods: ['POST'])]
    public function create(CreateProductRequest $request): Response
    {
        /** @var CreateProductCommand $command */
        $command = $this->getCommand(CreateProductCommand::class, $request->toArray());
        $this->commandBus->dispatch($command);
        return $this->acceptRequest();
    }

    #[Route('/edit', name: 'product_edit', methods: ['PUT'])]
    public function edit(EditProductRequest $request): Response
    {
        /** @var ChangeProductName $command */
        $command = $this->getCommand(ChangeProductName::class, $request->toArray());
        $this->commandBus->dispatch($command);
        return $this->response([
            'status' => sprintf('Product was edited : %s', $command->name()),
            'product_id' => $command->id()->__toString(),
        ]);
    }
}
