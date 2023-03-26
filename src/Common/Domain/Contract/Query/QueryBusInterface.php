<?php

namespace CQRS\Common\Domain\Contract\Query;

interface QueryBusInterface
{

    /** @return mixed */
    public function handle(QueryInterface $query);
}