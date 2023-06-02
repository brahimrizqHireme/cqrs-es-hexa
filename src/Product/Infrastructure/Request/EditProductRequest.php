<?php

namespace CQRS\Product\Infrastructure\Request;

use CQRS\Common\Infrastructure\Request\AbstractRequestResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EditProductRequest extends AbstractRequestResolver
{
    #[Assert\NotBlank([])]
    #[Assert\Uuid]
    protected string $id;

    #[Assert\NotBlank()]
    protected string $name;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}