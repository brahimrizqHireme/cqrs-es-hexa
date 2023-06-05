<?php

namespace CQRS\Common\Domain\Exception;

use Attribute;
use CQRS\Common\Domain\Contract\ApiExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

#[Attribute(Attribute::TARGET_CLASS)]
class UnprocessableEntity implements ApiExceptionInterface
{
    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }
}