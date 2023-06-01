<?php

namespace CQRS\Product\Infrastructure\Http\V1;

use CQRS\Common\Domain\Contract\Command\CommandBusInterface;
use CQRS\Common\Infrastructure\Http\BaseApiController;
use CQRS\Product\Application\Command\ChangeProductName;
use CQRS\Product\Application\Command\CreateProduct;
use CQRS\Product\Application\ValueObject\ProductId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/product')]
class ProductController extends BaseApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly ValidatorInterface $validator,
    )
    {
//        parent::__construct();
    }

    #[Route('/create', name: 'product_create')]
    public function create(): Response
    {
        $command = new CreateProduct(
            ProductId::generate(),
            'test name',
            ''
        );

        $errors = $this->validator->validate($command);
        $this->commandBus->dispatch($command);

        return new JsonResponse([
            'status' => 'Product was created',
            'product_id' => $command->id()->toString(),
        ], JsonResponse::HTTP_ACCEPTED);
    }

    #[Route('/edit/{id}', name: 'product_edit')]
    public function edit(string $id): Response
    {
        $name = sprintf('Product was Edited %s', random_int(0,300));
        $command = new ChangeProductName(
            ProductId::fromString($id),
            $name
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse([
            'status' => $name,
            'product_id' => $command->id()->toString(),
        ], JsonResponse::HTTP_ACCEPTED);
    }
}
