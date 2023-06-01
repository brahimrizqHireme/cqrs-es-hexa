<?php

namespace CQRS\Common\Domain\Trait;

use CQRS\Common\Domain\Enum\Database;
use MongoDB\BSON\Timestamp;
use MongoDB\BSON\Type;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\DeleteResult;
use MongoDB\InsertManyResult;
use MongoDB\InsertOneResult;
use MongoDB\UpdateResult;

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

    public function update(array $criteria, array $set, array $options = []): UpdateResult
    {
        return $this->mainCollection->updateOne($criteria, $set, $options);
    }

    public function updateMany(array $criteria, array $set, array $options = []): UpdateResult
    {
        return $this->mainCollection->updateMany($criteria, $set, $options);
    }

    public function count(array $query = [], array $options = []): int
    {
        return $this->mainCollection->countDocuments($query, $options);
    }

    public function insert(array $data, array $options = []): InsertOneResult
    {
        return $this->mainCollection->insertOne($data, $options);
    }

    public function insertMany(array $data, array $options = []): InsertManyResult
    {
        return $this->mainCollection->insertMany($data, $options);
    }

    public function findAll(): array
    {
        return $this->mainCollection->find()->toArray();
    }

    public function find(array $query = [], array $fields = [], array $context = []): array
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

        return $this->mainCollection->find($query, $options)->toArray();
    }

    public function findById(string $id): object|array|null
    {
        return $this->findOne(['_id' => $id]);
    }

    public function findOne(array $query = [], array $fields = [], array $options = []): object|array|null
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

        return $this->mainCollection->findOne($query, $queryOptions);
    }

    public function findAndModify(
        array $query,
        array $update = null,
        array $fields = null,
        array $options = []
    ): object|array|null {

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

        return $this->mainCollection->findOneAndUpdate($query, $update, $queryOptions);
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function toLegacy($value): mixed
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

    /**
     * @param $value
     * @return int|mixed
     */
    private static function convertBSONObjectToLegacy($value): mixed
    {
        switch (true) {
            case $value instanceof \MongoTimestamp:
            case $value instanceof Timestamp:
                return $value->sec;
            case $value instanceof \MongoDate:
            case $value instanceof UTCDateTime:
                return $value->toDateTime()->getTimestamp();
            default:
                return $value;
        }
    }

    public function aggregate(array $pipeline, array $op = []): array
    {
        $op['useCursor'] = true;
        $op['batchSize'] = self::$BATCH_SIZE;
        return $this->mainCollection->aggregate($pipeline, $op)->toArray();
    }

    public function remove(array $criteria = [], array $options = []): DeleteResult
    {
        return $this->mainCollection->deleteOne($criteria, $options);
    }

    public function removeMany(array $criteria = [], array $options = []): DeleteResult
    {
        return $this->mainCollection->deleteMany($criteria, $options);
    }

    public function distinct(string $fieldName, array $criteria = [], array $options = []): array
    {
        $result = $this->mainCollection->distinct($fieldName, $criteria, $options);
        if (isset($options[self::$LIMIT]) && is_int($options[self::$LIMIT]) && 0 != $options[self::$LIMIT]) {
            $result = array_slice($result, 0, $options[self::$LIMIT]);
        }

        return $result;
    }
}