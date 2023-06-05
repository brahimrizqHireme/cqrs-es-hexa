<?php

namespace CQRS\Common\Domain\Contract\Persistence\Result;

interface DistinctResultInterface
{
    public function results(): array;

}