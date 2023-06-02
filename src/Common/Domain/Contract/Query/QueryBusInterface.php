<?php

namespace CQRS\Common\Domain\Contract\Query;

interface QueryBusInterface
{
    public function handle(QueryInterface $message): mixed;
}