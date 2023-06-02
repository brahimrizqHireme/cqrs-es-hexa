<?php

namespace CQRS\Common\Domain\Contract\Persistence\Result;

interface InsertResultInterface
{
    public function inserted(): bool;

}