<?php

declare(strict_types=1);

namespace CQRS\Common\Domain\Enum;
enum Database : string
{
    case SELECTED_DATABASE = 'cqrs';
}