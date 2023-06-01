<?php

namespace CQRS\Common\Infrastructure\Http;

use CQRS\Common\Domain\Contract\Command\CommandInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseApiController extends AbstractController
{
//    public function __construct(private readonly ValidatorInterface $validator)
//    {
//    }

    public function version(Request $request): Response
    {
        return new JsonResponse(
            [
                'version' => $this->getParameter('API_VERSION'),
                'status' => 'ok',
            ],
            JsonResponse::HTTP_ACCEPTED
        );
    }

    private function acceptRequest(): Response
    {
        $response = new Response();
        $response->setStatusCode(202);

        return $response;
    }

    private function response(array $data, int $statusCode = JsonResponse::HTTP_OK): JsonResponse|Response
    {
        return new JsonResponse($data, $statusCode);
    }

    public function validate(CommandInterface $command): void
    {

    }
}
