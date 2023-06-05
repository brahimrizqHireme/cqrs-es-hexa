<?php

namespace CQRS\Common\Domain\Contract\Persistence\Result;

interface UpdateResultInterface
{
    public function updated(): bool;
}