<?php

namespace CQRS\Common\Domain\Contract\Persistence\Result;

interface DeleteResultInterface
{
    public function deleted(): bool;
    public function count(): int;

}