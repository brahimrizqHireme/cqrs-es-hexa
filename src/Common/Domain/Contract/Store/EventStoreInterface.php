<?php

namespace CQRS\Common\Domain\Contract\Store;

use EventSauce\EventSourcing\MessageRepository;

interface EventStoreInterface extends MessageRepository
{

}