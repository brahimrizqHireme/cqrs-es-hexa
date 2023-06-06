<?php

namespace CQRS\Product\Domain\Exception;

use CQRS\Common\Domain\Contract\ApiExceptionInterface;
use CQRS\Common\Domain\Exception\AccessDenied;
use CQRS\Common\Domain\Exception\ApiException;

#[AccessDenied]
class ProductAccessDeniedExceptions extends ApiException
{
    public static function permissionDenied(): ApiExceptionInterface
    {
        return new self('Permission Denied.');
    }
}