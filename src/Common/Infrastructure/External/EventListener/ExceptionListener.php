<?php

namespace CQRS\Common\Infrastructure\External\EventListener;

use CQRS\Common\Domain\Contract\ApiExceptionInterface;
use CQRS\Common\Domain\Contract\LoggerInterface;
use Exception;
use ReflectionClass;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

#[AsEventListener(method: 'handleException')]
readonly class ExceptionListener
{
    const APPLICATION_JSON = 'application/json';

    public function __construct(private LoggerInterface $logger)
    {
    }

    public function handleException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $this->log($exception);

        while ($exception->getPrevious()) {
            $exception = $exception->getPrevious();
        }

        $attribute = $this->getAttribute($exception);
        $response = new JsonResponse();

        if (!empty($attribute) && $attribute instanceof ApiExceptionInterface) {
            $response->setStatusCode($attribute->getStatusCode());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response->setData([
            'message' => $exception->getMessage(),
            'code' => $response->getStatusCode(),
            'error' => true
        ]);

        $event->setResponse($response);


    }

//    private function createApiResponse(Throwable $exception): Response
//    {
//        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
//        $errors     = [];
//
//        return new ApiResponse($exception->getMessage(), null, $errors, $statusCode);
//    }


    private function getAttribute(Throwable $exception): ?ApiExceptionInterface
    {
        $reflectionClass = new ReflectionClass($exception);
        $attributes = $reflectionClass->getAttributes() ;

        $instances = array_map(function (object $attribute) {
            $instance = $attribute->newInstance();
            if ($instance instanceof ApiExceptionInterface) {
                return $instance;
            }
            return null;
        }, $attributes);

        if (empty($instances)) {
            return null;
        }

        return $instances[array_key_first($instances)];
    }

    private function log(Throwable $exception): void
    {
        $log = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'called' => [
                'file' => $exception->getTrace()[0]['file'],
                'line' => $exception->getTrace()[0]['line'],
            ],
            'occurred' => [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ],
        ];

        if ($exception->getPrevious() instanceof Exception) {
            $log += [
                'previous' => [
                    'message' => $exception->getPrevious()->getMessage(),
                    'exception' => get_class($exception->getPrevious()),
                    'file' => $exception->getPrevious()->getFile(),
                    'line' => $exception->getPrevious()->getLine(),
                ],
            ];
        }

        $this->logger->error(json_encode($log));
    }
}