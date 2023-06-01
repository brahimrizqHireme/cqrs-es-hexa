<?php

namespace CQRS\Product\Infrastructure\External\ServiceFactory;

use CQRS\Common\Infrastructure\External\Database\MongodbClient;
use CQRS\Common\Infrastructure\External\EventSauce\AggregateRepository;
use CQRS\Common\Infrastructure\External\EventSauce\ChainSnapshotRepository;
use CQRS\Common\Infrastructure\External\EventSauce\MongoDbMessageRepository;
use CQRS\Common\Infrastructure\External\EventSauce\MongodbSnapshotRepository;
use CQRS\Product\Application\Decorator\EnrichRequestHeadersMessageDecorator;
use CQRS\Product\Application\Repository\ProductRepository;
use CQRS\Product\Domain\Aggregate\Product;
use CQRS\Product\Domain\Projector\ProductProjector;
use EventSauce\EventSourcing\DefaultHeadersDecorator;
use EventSauce\EventSourcing\MessageDecoratorChain;
use EventSauce\EventSourcing\Snapshotting\ConstructingAggregateRootRepositoryWithSnapshotting;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use Symfony\Component\Serializer\SerializerInterface;

final class ProductRepositoryFactory
{
    const COLLECTION = 'product';

    public function __construct(
        private readonly MongodbClient $mongoClient,
        private readonly SerializerInterface $serializer
    ){
    }

    public function create(): ProductRepository
    {
        $messageRepository = new MongoDbMessageRepository($this->mongoClient, self::COLLECTION);
        $mongodbSnapshotRepository = new MongodbSnapshotRepository(
            $this->mongoClient,
            $this->serializer,
            self::COLLECTION
        );

        $decoratorChain = new MessageDecoratorChain(
            new DefaultHeadersDecorator(),
            new EnrichRequestHeadersMessageDecorator()
        );

        return new ProductRepository(
            new ConstructingAggregateRootRepositoryWithSnapshotting(
                Product::class,
                $messageRepository,
                new ChainSnapshotRepository(
                    $mongodbSnapshotRepository
                ),
                new AggregateRepository(
                    Product::class,
                    $messageRepository,
                    new SynchronousMessageDispatcher(
                        new ProductProjector($this->mongoClient),
                    ),
                    $decoratorChain
                )
            )
        );
    }
}