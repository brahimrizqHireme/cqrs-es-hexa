<?php

namespace CQRS\Common\Domain\Exception;

use CQRS\Common\Domain\Contract\ApiExceptionInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class ApiException extends RuntimeException implements ApiExceptionInterface
{
    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }
}