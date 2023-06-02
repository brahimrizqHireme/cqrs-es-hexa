<?php

namespace CQRS\Product\Domain\Exception;

use CQRS\Common\Domain\Exception\InvalidArgumentException;

class ProductExceptions extends InvalidArgumentException
{
    public static function notFound(?string $message = null): ProductExceptions
    {
        if (!empty($message)) {
            return new self($message);
        }

        return new self('Product was not found.');
    }
}