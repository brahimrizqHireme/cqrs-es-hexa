<?php

namespace CQRS\Product\Domain\Event;

use CQRS\Common\Domain\Event\AggregateChanged;

class ProductWasCreated extends AggregateChanged
{
    public function __construct(
        private string $id,
        private string $name,
        private string $description
    )
    {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }


    public function toPayload(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(
            $payload['id'],
            $payload['name'],
            $payload['description'],
        );
    }
}