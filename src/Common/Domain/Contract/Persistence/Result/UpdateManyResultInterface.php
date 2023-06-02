<?php

namespace CQRS\Common\Domain\Contract\Persistence\Result;

interface UpdateManyResultInterface
{
    public function updated(): bool;
    public function count(): bool;
}