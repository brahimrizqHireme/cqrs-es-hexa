<?php

namespace CQRS\Common\Domain\Contract\Query;

use CQRS\Common\Domain\Contract\Persistence\PersistenceCountByQueryInterface;
use CQRS\Common\Domain\Contract\Persistence\PersistenceDistinctInterface;
use CQRS\Common\Domain\Contract\Persistence\PersistenceFindAllInterface;
use CQRS\Common\Domain\Contract\Persistence\PersistenceFindAndModifyInterface;
use CQRS\Common\Domain\Contract\Persistence\PersistenceFindByIdInterface;
use CQRS\Common\Domain\Contract\Persistence\PersistenceFindByQueryInterface;
use CQRS\Common\Domain\Contract\Persistence\PersistenceFindOneInterface;

interface QueryFinderAdapterInterface extends
    PersistenceCountByQueryInterface,
    PersistenceDistinctInterface,
    PersistenceFindAndModifyInterface,
    PersistenceFindByIdInterface,
    PersistenceFindOneInterface,
    PersistenceFindAllInterface,
    PersistenceFindByQueryInterface
{
}