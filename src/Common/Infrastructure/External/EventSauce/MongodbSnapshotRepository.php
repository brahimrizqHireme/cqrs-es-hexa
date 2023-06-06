<?php

namespace CQRS\Common\Infrastructure\External\EventSauce;


use CQRS\Common\Domain\Helper\CommonService;
use CQRS\Common\Domain\Trait\MongoDbTrait;
use CQRS\Common\Infrastructure\External\Database\MongodbClient;
use Doctrine\DBAL\Exception;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\Snapshot;
use EventSauce\EventSourcing\Snapshotting\SnapshotRepository;
use MongoDB\BSON\UTCDateTime;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class MongodbSnapshotRepository implements SnapshotRepository
{
    use MongoDbTrait;
    private string $tableSuffix = 'snapshot';

    private const SORT_ASCENDING = -1;
    public function __construct(
        private readonly MongodbClient     $mongoClient,
        private readonly SerializerInterface $serializer,
        private readonly string $collection
    ) {
        $this->mainCollection = $this->getCollection(sprintf('%s_%s', $collection, $this->tableSuffix));
    }

    /**
     * @throws \Exception
     */
    public function persist(Snapshot $snapshot): void
    {
        $id = $snapshot->aggregateRootId()->__toString();
        $version = $snapshot->aggregateRootVersion();

        $type = get_class($snapshot->state());
        $jsonState = $this->serializer->serialize(
            $snapshot->state(),
            JsonEncoder::FORMAT,
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['eventName']]
        );
        $payload = json_decode($jsonState, true);
        $payload['id'] = $id;

        try {
            $this->update(
                ['_id' => $id],
                [
                   '$set' => [
                       '_id' => $id,
                       'aggregate_event_name' => $snapshot->state()->getEventName(),
                       'aggregate_root_version' => $version,
                       'aggregate_type' => $type,
                       'payload' => $payload,
                       'headers' => CommonService::getClientDataFromServer(),
                       'created_at' => new UTCDateTime(),
                   ]
                ],
                ['upsert' => true]
            );

        } catch (Exception $e) {
            throw new \Exception(sprintf('Snapshot failed to save event with message %s', $e->getMessage()));
        }
    }

    public function retrieve(AggregateRootId $id): ?Snapshot
    {
        $options = ['sort' => ['aggregate_root_version' => self::SORT_ASCENDING]];
        $project = ['_id' => 1, 'payload' => 1, 'aggregate_root_version' => 1, 'aggregate_type' => 1];
        $result = $this->findOne(['_id' => $id->__toString()], $project, $options)->results();

        if (empty($result)) {
            return null;
        }
        $deserialized = $this->serializer->deserialize(json_encode($result['payload']), $result['aggregate_type'], JsonEncoder::FORMAT);
        return new Snapshot($id, $result['aggregate_root_version'], $deserialized);
    }

}