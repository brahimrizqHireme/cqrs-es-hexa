<?php

namespace CQRS\Product\Domain\Exception;

use CQRS\Common\Domain\Contract\ApiExceptionInterface;
use CQRS\Common\Domain\Exception\DomainException;
use CQRS\Common\Domain\Exception\NotFound;

#[NotFound]
class ProductExceptions extends DomainException implements ApiExceptionInterface
{
    public static function notFound(): ApiExceptionInterface
    {
        return new self('Product was not found.');
    }
}