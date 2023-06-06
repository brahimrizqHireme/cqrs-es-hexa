<?php

namespace CQRS\Product\Infrastructure\Presentation\Request;

use CQRS\Common\Infrastructure\Presentation\Request\AbstractRequestResolver;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

//#[When(env: 'dev')]
class CreateProductRequest extends AbstractRequestResolver
{
//    #[Assert\Type(AggregateRootId::class)]
    #[SerializedName("id")]
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