<?php

namespace CQRS\Common\Infrastructure\Http\V1;

use CQRS\Common\Infrastructure\Http\BaseApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseApiController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return new JsonResponse(
            [
                'home' => 'page',
                'status' => 'ok',
            ],
            JsonResponse::HTTP_ACCEPTED
        );
//        return $this->render('home/index.html.twig', [
//            'controller_name' => 'HomeController',
//        ]);
    }
}
