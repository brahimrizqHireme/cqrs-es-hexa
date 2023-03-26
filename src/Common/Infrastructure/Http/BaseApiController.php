<?php

namespace CQRS\Common\Infrastructure\Http;

use CQRS\Common\Infrastructure\Model\Database\MongodbClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController extends AbstractController
{
    public function version(): Response
    {
        return new JsonResponse(
            [
                'version' => $this->getParameter('API_VERSION'),
                'status' => 'ok',
            ],
            JsonResponse::HTTP_ACCEPTED
        );
    }
}
