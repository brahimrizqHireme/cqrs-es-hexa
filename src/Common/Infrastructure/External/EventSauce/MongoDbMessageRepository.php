<?php

namespace CQRS\Common\Infrastructure\External\EventSauce;

use CQRS\Common\Domain\Trait\MongoDbTrait;
use CQRS\Common\Infrastructure\External\Database\MongodbClient;
use CQRS\Common\Infrastructure\External\SymfonyUuidGenerator;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\OffsetCursor;
use EventSauce\EventSourcing\PaginationCursor;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use EventSauce\EventSourcing\UnableToPersistMessages;
use EventSauce\EventSourcing\UnableToRetrieveMessages;
use EventSauce\MessageRepository\TableSchema\DefaultTableSchema;
use EventSauce\MessageRepository\TableSchema\TableSchema;
use EventSauce\UuidEncoding\StringUuidEncoder;
use EventSauce\UuidEncoding\UuidEncoder;
use Generator;
use LogicException;
use MongoDB\Driver\WriteConcern;
use Throwable;
use function count;
use function get_class;
use function json_decode;
use function sprintf;

class MongoDbMessageRepository implements MessageRepository
{
    use MongoDbTrait;

    private const SORT_ASCENDING = 1;
    private MessageSerializer $serializer;
    private TableSchema $tableSchema;
    private UuidEncoder $uuidEncoder;
    private string $tableSuffix = 'event_stream';

    public function __construct(
        private readonly MongodbClient $mongoClient,
        private readonly string $collection
    )
    {
        $this->serializer = new ConstructingMessageSerializer();
        $this->tableSchema = new DefaultTableSchema();
        $this->uuidEncoder = new StringUuidEncoder();
        $this->mainCollection = $this->getCollection(sprintf('%s_%s', $this->collection, $this->tableSuffix));
    }

    public function persist(Message ...$messages): void
    {
        if (count($messages) === 0) {
            return;
        }

        $documents = [];
        foreach ($messages as $index => $message) {
            $payload = $this->serializer->serializeMessage($message);
            $payload['headers'][Header::EVENT_ID] ??= SymfonyUuidGenerator::v4()->__toString();
            $document = [
                    '_id' => $this->uuidEncoder->encodeString($payload['headers'][Header::EVENT_ID]),
                    'aggregate_root_id' => $this->uuidEncoder->encodeString($payload['headers'][Header::AGGREGATE_ROOT_ID]),
                    'version' => $payload['headers'][Header::AGGREGATE_ROOT_VERSION] ?? 0,
                    'created_at' => time(),
                    'payload' => $payload['payload'],
                    'headers' => $payload['headers'],
                ] + $this->tableSchema->additionalColumns();

            $documents[] = $document;
        }

        try {
            $this->insertMany($documents, ['writeConcern' => new WriteConcern('majority')]);
        } catch (Throwable $exception) {
            throw UnableToPersistMessages::dueTo('', $exception);
        }
    }

    public function retrieveAll(AggregateRootId $id): Generator
    {
        $options = [
            'sort' => ['version' => self::SORT_ASCENDING],
        ];

        $cursor = $this->find(['aggregate_root_id' => $id->__toString()], $options)->results();

        try {
            return $this->yieldMessagesFromPayloads($cursor);
        } catch (Throwable $exception) {
            throw UnableToRetrieveMessages::dueTo('', $exception);
        }
    }

    public function retrieveAllAfterVersion(AggregateRootId $id, int $aggregateRootVersion): Generator
    {
        $options = ['sort' => ['version' => self::SORT_ASCENDING]];

        $cursor = $this->find(['aggregate_root_id' => $id->__toString(), 'version' => $aggregateRootVersion],[], $options)->results();

        try {
            return $this->yieldMessagesFromPayloads($cursor);
        } catch (Throwable $exception) {
            throw UnableToRetrieveMessages::dueTo('', $exception);
        }
    }

    private function yieldMessagesFromPayloads(iterable $payloads): Generator
    {
        foreach ($payloads as $payload) {
            yield $message = $this->serializer->unserializePayload($payload);
        }

        return isset($message)
            ? $message->header(Header::AGGREGATE_ROOT_VERSION) ?: 0
            : 0;
    }

    public function paginate(PaginationCursor $cursor): Generator
    {
        if (!$cursor instanceof OffsetCursor) {
            throw new LogicException(sprintf('Wrong cursor type used, expected %s, received %s', OffsetCursor::class, get_class($cursor)));
        }

        $options = [
            'sort' => [
                'created_at' => self::SORT_ASCENDING,
                'version' => self::SORT_ASCENDING
            ],
            'skip' => $cursor->offset(),
            'limit' => $cursor->limit()
        ];

        $resultCursor = $this->find([], ['payload' => 1], $options)->results();
        $numberOfMessages = 0;
        try {
            foreach ($resultCursor as $payload) {
                $numberOfMessages++;
                yield $this->serializer->unserializePayload(json_decode($payload, true));
            }
        } catch (Throwable $exception) {
            throw UnableToRetrieveMessages::dueTo($exception->getMessage(), $exception);
        }

        return $cursor->plusOffset($numberOfMessages);
    }
}