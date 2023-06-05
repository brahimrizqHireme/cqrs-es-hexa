<?php

namespace CQRS\Common\Domain\Contract;

interface ApiExceptionInterface {
    public function getStatusCode(): int;
}