<?php

namespace CQRS\Common\Domain\Exception;

use Attribute;
use CQRS\Common\Domain\Contract\ApiExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

#[Attribute(Attribute::TARGET_CLASS)]
class NotFound implements ApiExceptionInterface
{
    public function getStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}