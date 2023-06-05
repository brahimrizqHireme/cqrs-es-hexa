<?php

namespace CQRS\Common\Domain\Contract\Persistence\Result;

interface InsertManyResultInterface
{
    public function inserted(): bool;
    public function count(): int;

}