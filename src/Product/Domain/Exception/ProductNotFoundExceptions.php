<?php

namespace CQRS\Product\Domain\Exception;

use CQRS\Common\Domain\Contract\ApiExceptionInterface;
use CQRS\Common\Domain\Exception\ApiException;
use CQRS\Common\Domain\Exception\NotFound;

#[NotFound]
class ProductNotFoundExceptions extends ApiException
{
    public static function notFound(): ApiExceptionInterface
    {
        return new self('Product was not found.');
    }
}