<?php

namespace CQRS\Common\Domain\Exception;

use CQRS\Common\Domain\Contract\ApiExceptionInterface;
use Exception as BaseException;
use Symfony\Component\HttpFoundation\Response;

class DomainException extends BaseException implements ApiExceptionInterface
{
    const DEFAULT_CODE = Response::HTTP_UNPROCESSABLE_ENTITY;
    public function __construct(string $message, int $code = self::DEFAULT_CODE, BaseException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withApplyFunction(string $class, string $methodName): DomainException
    {
        return new self(
            sprintf('Method %s was not found in class %s', $methodName, $class),
            self::DEFAULT_CODE
        );
    }

    public static function propertyNotFound(string $class, string $property): DomainException
    {
        return new self(
            sprintf('Property %s was not found in request %s', $property, $class),
            self::DEFAULT_CODE
        );
    }

    public function getStatusCode(): int
    {
        return self::DEFAULT_CODE;
    }
}