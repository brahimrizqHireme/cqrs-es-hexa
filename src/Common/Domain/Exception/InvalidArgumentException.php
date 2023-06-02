<?php

namespace CQRS\Common\Domain\Exception;

use CQRS\Common\Domain\Contract\BaseExceptionInterface;

class InvalidArgumentException extends \Exception implements BaseExceptionInterface
{

}