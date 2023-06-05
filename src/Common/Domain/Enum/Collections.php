<?php

declare(strict_types=1);

namespace CQRS\Common\Domain\Enum;
enum Collections : string
{
    case PRODUCT_COLLECTION = 'read_product';
}