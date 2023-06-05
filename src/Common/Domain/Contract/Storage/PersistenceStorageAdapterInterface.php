<?php

namespace CQRS\Common\Domain\Contract\Storage;

use CQRS\Common\Domain\Contract\Persistence\PersistenceInsertInterface;
use CQRS\Common\Domain\Contract\Persistence\PersistenceInsertManyInterface;
use CQRS\Common\Domain\Contract\Persistence\PersistenceRemoveInterface;
use CQRS\Common\Domain\Contract\Persistence\PersistenceRemoveManyInterface;
use CQRS\Common\Domain\Contract\Persistence\PersistenceUpdateInterface;
use CQRS\Common\Domain\Contract\Persistence\PersistenceUpdateManyInterface;

interface PersistenceStorageAdapterInterface extends
    PersistenceRemoveInterface,
    PersistenceRemoveManyInterface,
    PersistenceUpdateManyInterface,
    PersistenceInsertInterface,
    PersistenceUpdateInterface,
    PersistenceInsertManyInterface
//    PersistenceCountByQueryInterface,
//    PersistenceFindOneInterface,
//    PersistenceDistinctInterface,
//    PersistenceFindAndModifyInterface,
//    PersistenceFindAllInterface,
//    PersistenceFindByIdInterface,
//    PersistenceFindByQueryInterface
{}