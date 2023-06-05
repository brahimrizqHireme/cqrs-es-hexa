<?php

namespace CQRS\Product\Infrastructure\Presentation\Request;

use CQRS\Common\Infrastructure\Presentation\Request\AbstractRequestResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EditProductRequest extends AbstractRequestResolver
{
    #[Assert\NotBlank([])]
    #[Assert\Uuid]
    protected string $id;

    #[Assert\NotBlank()]
//    #[Assert\Type('integer')]
//    #[Assert\Length(min: 5, max: 30)]
    protected string $name;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}