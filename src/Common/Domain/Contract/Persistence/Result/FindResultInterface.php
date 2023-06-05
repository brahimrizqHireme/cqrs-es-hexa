<?php

namespace CQRS\Common\Domain\Contract\Persistence\Result;

interface FindResultInterface
{
    public function results(): array;
}