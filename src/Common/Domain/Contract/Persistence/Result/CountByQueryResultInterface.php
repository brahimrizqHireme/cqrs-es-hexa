<?php

namespace CQRS\Common\Domain\Contract\Persistence\Result;

interface CountByQueryResultInterface
{
    public function count(): int;

}