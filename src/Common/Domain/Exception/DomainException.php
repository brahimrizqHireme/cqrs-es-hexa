<?php

namespace CQRS\Common\Domain\Exception;

use Exception as BaseException;
use Symfony\Component\HttpFoundation\Response;

class DomainException extends BaseException
{
    public function __construct(string $message, int $code = Response::HTTP_BAD_REQUEST, BaseException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withApplyFunction(string $class, string $methodName): DomainException
    {
        return new self(
            sprintf('Method %s was not found in class %s', $methodName, $class)
        );
    }

    public static function withCommandArgumentResolver(): DomainException
    {
        return new self('Command argument resolver attribute not found.');
    }

    public static function propertyNotFound(string $class, string $property): DomainException
    {
        return new self(
            sprintf('Property %s was not found in request %s', $property, $class)
        );
    }
}