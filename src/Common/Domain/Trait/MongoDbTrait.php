<?php

namespace CQRS\Common\Domain\Trait;

use CQRS\Common\Domain\Contract\Persistence\Result\CountByQueryResultInterface;
use CQRS\Common\Domain\Contract\Persistence\Result\DeleteResultInterface;
use CQRS\Common\Domain\Contract\Persistence\Result\DistinctResultInterface;
use CQRS\Common\Domain\Contract\Persistence\Result\FindOneResultInterface;
use CQRS\Common\Domain\Contract\Persistence\Result\FindResultInterface;
use CQRS\Common\Domain\Contract\Persistence\Result\InsertManyResultInterface;
use CQRS\Common\Domain\Contract\Persistence\Result\InsertResultInterface;
use CQRS\Common\Domain\Contract\Persistence\Result\UpdateManyResultInterface;
use CQRS\Common\Domain\Contract\Persistence\Result\UpdateResultInterface;
use CQRS\Common\Domain\Enum\Database;
use CQRS\Common\Infrastructure\External\Persistence\Mongodb\MongodbCountByQueryResult;
use CQRS\Common\Infrastructure\External\Persistence\Mongodb\MongodbDistinctResult;
use CQRS\Common\Infrastructure\External\Persistence\Mongodb\MongodbFindAllFindResult;
use CQRS\Common\Infrastructure\External\Persistence\Mongodb\MongodbFindByQueryResult;
use CQRS\Common\Infrastructure\External\Persistence\Mongodb\MongodbFindOneResult;
use CQRS\Common\Infrastructure\External\Persistence\Mongodb\MongodbInsertManyResult;
use CQRS\Common\Infrastructure\External\Persistence\Mongodb\MongodbInsertResult;
use CQRS\Common\Infrastructure\External\Persistence\Mongodb\MongodbRemoveManyResult;
use CQRS\Common\Infrastructure\External\Persistence\Mongodb\MongodbRemoveResult;
use CQRS\Common\Infrastructure\External\Persistence\Mongodb\MongodbUpdateManyResult;
use CQRS\Common\Infrastructure\External\Persistence\Mongodb\MongodbUpdateResult;
use MongoDB\BSON\Timestamp;
use MongoDB\BSON\Type;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;

trait MongoDbTrait
{
    public static $SORT = 'sort';
    public static $SKIP = 'skip';
    public static $LIMIT = 'limit';
    public static $BATCH_SIZE = 1000;

    protected Collection $mainCollection;

    public function getCollection(string $collection): Collection
    {
        return $this->mongoClient->selectDatabase(Database::SELECTED_DATABASE->value)->selectCollection($collection);
    }

    public function update(array $criteria, array $set, array $options = []): UpdateResultInterface
    {
        return new MongodbUpdateResult($this->mainCollection->updateOne($criteria, $set, $options));
    }

    public function updateMany(array $criteria, array $set, array $options = []): UpdateManyResultInterface
    {
        return new MongodbUpdateManyResult($this->mainCollection->updateMany($criteria, $set, $options));
    }

    public function count(array $query = [], array $options = []): CountByQueryResultInterface
    {
        new MongodbCountByQueryResult($this->mainCollection->countDocuments($query, $options));
    }

    public function insert(array $data, array $options = []): InsertResultInterface
    {
        return new MongodbInsertResult($this->mainCollection->insertOne($data, $options));
    }

    public function insertMany(array $data, array $options = []): InsertManyResultInterface
    {
        return new MongodbInsertManyResult($this->mainCollection->insertMany($data, $options));
    }

    public function findAll(): FindResultInterface
    {
        return new MongodbFindAllFindResult($this->mainCollection->find());
    }

    public function find(array $query = [], array $fields = [], array $context = []): FindResultInterface
    {
        $options = [];
        if (!empty($fields)) {
            $options['projection'] = $fields;
        }

        if (isset($context[self::$SORT]) && 0 !== count($context[self::$SORT])) {
            $options['sort'] = $context[self::$SORT];
        }
        if (isset($context[self::$SKIP])) {
            $options['skip'] = (int)$context[self::$SKIP];
        }
        if (isset($context[self::$LIMIT])) {
            $options['limit'] = (int)$context[self::$LIMIT];
        }

        return new MongodbFindByQueryResult($this->mainCollection->find($query, $options));
    }

    public function findById(string $id): FindOneResultInterface
    {
        return new MongodbFindOneResult((array)$this->findOne(['_id' => $id]));
    }

    public function findOne(array $query = [], array $fields = [], array $options = []): FindOneResultInterface
    {
        $queryOptions = [];
        if (!empty($fields)) {
            $queryOptions['projection'] = $fields;
        }

        if (isset($options[self::$SORT]) && 0 !== count($options[self::$SORT])) {
            $queryOptions['sort'] = $options[self::$SORT];
        }
        if (isset($options[self::$SKIP])) {
            $queryOptions['skip'] = (int)$options[self::$SKIP];
        }

        return new MongodbFindOneResult($this->mainCollection->findOne($query, $queryOptions));
    }

    public function findAndModify(
        array $query,
        array $update = null,
        array $fields = null,
        array $options = []
    ): FindOneResultInterface {

        $queryOptions = [];
        if (!empty($fields)) {
            $queryOptions['projection'] = $fields;
        }

        if (isset($options[self::$SORT]) && 0 !== count($options[self::$SORT])) {
            $queryOptions['sort'] = $options[self::$SORT];
        }
        if (isset($options[self::$SKIP])) {
            $queryOptions['skip'] = (int)$options[self::$SKIP];
        }

        return new MongodbFindOneResult($this->mainCollection->findOneAndUpdate($query, $update, $queryOptions));
    }

    public static function toLegacy(mixed $value): mixed
    {
        switch (true) {
            case $value instanceof Type:
                return self::convertBSONObjectToLegacy($value);
            case is_array($value):
                $result = [];
                foreach ($value as $key => $item) {
                    $result[$key] = self::toLegacy($item);
                }

                return $result;
            case is_object($value):
                $result = [];
                foreach ($value as $key => $item) {
                    $result[] = self::toLegacy($item);
                }

                return $result;
            default:
                return $value;
        }
    }

    private static function convertBSONObjectToLegacy(mixed $value): mixed
    {
        return match (true) {
            $value instanceof \MongoTimestamp, $value instanceof Timestamp => $value->sec,
            $value instanceof \MongoDate, $value instanceof UTCDateTime => $value->toDateTime()->getTimestamp(),
            default => $value,
        };
    }

    public function aggregate(array $pipeline, array $op = []): array
    {
        $op['useCursor'] = true;
        $op['batchSize'] = self::$BATCH_SIZE;
        return $this->mainCollection->aggregate($pipeline, $op)->toArray();
    }

    public function remove(array $criteria = [], array $options = []): DeleteResultInterface
    {
        return new MongodbRemoveResult($this->mainCollection->deleteOne($criteria, $options));
    }

    public function removeMany(array $criteria = [], array $options = []): DeleteResultInterface
    {
        return new MongodbRemoveManyResult($this->mainCollection->deleteMany($criteria, $options));
    }

    public function distinct(string $fieldName, array $criteria = [], array $options = []): DistinctResultInterface
    {
        $result = $this->mainCollection->distinct($fieldName, $criteria, $options);
        if (isset($options[self::$LIMIT]) && is_int($options[self::$LIMIT]) && 0 != $options[self::$LIMIT]) {
            $result = array_slice($result, 0, $options[self::$LIMIT]);
        }

        return new MongodbDistinctResult($result);
    }
}