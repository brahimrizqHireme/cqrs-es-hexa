<?php

namespace CQRS\Product\Infrastructure\External\ServiceFactory;

use CQRS\Common\Infrastructure\External\Database\MongodbClient;
use CQRS\Common\Infrastructure\External\EventSauce\EventAggregateRootRepository;
use CQRS\Common\Infrastructure\External\EventSauce\MongoDbEventStore;
use CQRS\Product\Application\Decorator\EnrichProductMessageDecorator;
use CQRS\Product\Application\Repository\ProductRepository;
use CQRS\Product\Domain\Aggregate\Product;
use CQRS\Product\Domain\Projector\ProductProjector;
use EventSauce\EventSourcing\DefaultHeadersDecorator;
use EventSauce\EventSourcing\MessageDecoratorChain;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;

readonly class ProductRepositoryFactory
{
    public function __construct(private MongodbClient $client){
    }

    public function create(): ProductRepository
    {
        $decoratorChain = new MessageDecoratorChain(
            new DefaultHeadersDecorator(),
            new EnrichProductMessageDecorator()
        );

        return new ProductRepository(
            new EventAggregateRootRepository(
                Product::class,
                new MongoDbEventStore($this->client),
                new SynchronousMessageDispatcher(
                    new ProductProjector(),
                ),
                $decoratorChain
            )
        );
    }
}