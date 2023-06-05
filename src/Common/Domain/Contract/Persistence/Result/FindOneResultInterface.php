<?php

namespace CQRS\Common\Domain\Contract\Persistence\Result;

interface FindOneResultInterface
{
    public function results(): array;

}