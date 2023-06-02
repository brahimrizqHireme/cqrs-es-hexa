<?php

namespace CQRS\Product\Infrastructure\Request;

use CQRS\Common\Infrastructure\Request\AbstractRequestResolver;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\Validator\Constraints as Assert;

//#[When(env: 'dev')]
class CreateProductRequest extends AbstractRequestResolver
{
//    #[Assert\Type(AggregateRootId::class)]
    #[Assert\NotBlank([])]
    #[Assert\Uuid]
    protected string $id;

    #[Assert\NotBlank()]
    protected string $name;

    #[Assert\NotBlank()]
    protected string $description;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}