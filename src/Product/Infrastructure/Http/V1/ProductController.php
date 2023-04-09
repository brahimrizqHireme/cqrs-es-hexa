<?php

namespace CQRS\Product\Infrastructure\Http\V1;

use CQRS\Common\Domain\Contract\Command\CommandBusInterface;
use CQRS\Common\Infrastructure\Http\BaseApiController;
use CQRS\Product\Application\Command\CreateProduct;
use CQRS\Product\Application\ValueObject\ProductId;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/create', name: 'product_create')]
    public function create(): Response
    {
        $command = new CreateProduct(
            ProductId::generate(),
            'test name',
            'test description'
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse([
            'status' => 'Product was created',
            'product_id' => $command->id()->toString(),
        ], JsonResponse::HTTP_ACCEPTED);
    }
}
