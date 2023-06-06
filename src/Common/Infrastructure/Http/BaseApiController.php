<?php

namespace CQRS\Common\Infrastructure\Http;

use CQRS\Common\Domain\Contract\Command\CommandInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController extends AbstractController
{
    public function version(Request $request): Response
    {
        return new JsonResponse(
            [
                'version' => $this->getParameter('API_VERSION'),
                'status' => 'ok',
            ],
            Response::HTTP_ACCEPTED
        );
    }

    protected function getCommand(string $commandName, array $payload): CommandInterface
    {
        if (! class_exists($commandName)) {
            throw new \UnexpectedValueException(sprintf('Given command class %s name is not a valid', $commandName));
        }

        if (!is_subclass_of($commandName, CommandInterface::class)) {
            throw new \UnexpectedValueException(sprintf(
                'Command class %s is not a instance of class of %s',
                $commandName,
                CommandInterface::class
            ));
        }
        /** @var $command CommandInterface */
        $command = call_user_func_array([$commandName, 'withData'], $payload);
        return $command;
    }

    protected function acceptRequest(): Response
    {
        $response = new Response();
        $response->setStatusCode(202);

        return $response;
    }

    protected function response(array $data, int $statusCode = Response::HTTP_OK): JsonResponse|Response
    {
        return new JsonResponse($data, $statusCode);
    }

    public function validate(CommandInterface $command): void
    {

    }
}
