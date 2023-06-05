<?php

namespace CQRS\Product\Domain\Exception;

use CQRS\Common\Domain\Contract\ApiExceptionInterface;
use CQRS\Common\Domain\Exception\AccessDenied;
use CQRS\Common\Domain\Exception\DomainException;

#[AccessDenied]
class ProductAccessDeniedExceptions extends DomainException
{
    public static function permissionDenied(): ApiExceptionInterface
    {
        return new self('Permission Denied.');
    }
}