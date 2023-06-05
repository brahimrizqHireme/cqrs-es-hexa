<?php

namespace CQRS\Common\Infrastructure\Presentation\Request;

use CQRS\Common\Domain\Exception\DomainException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequestResolver implements ValueResolverInterface
{
    private array $requestData;
    public function __construct(
        protected readonly ValidatorInterface $validator
    )
    {
    }

    abstract public function toArray(): array;

    /**
     * @throws DomainException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $class = $argument->getType();
        if (get_class($this) === $class) {
            $requestData = array_map('trim', $request->request->all());
            $requestData = array_merge($requestData, $request->toArray(), $request->files->all());
            $this->requestData = $requestData;
            $this->populate();
            if ($this->autoValidateRequest()) {
                $this->validate();
            }
            yield $this;
        }
    }

    public function validate(): void
    {
        $errors = $this->validator->validate($this);
        $messages = ['message' => 'validation_failed', 'code' => Response::HTTP_UNPROCESSABLE_ENTITY, 'errors' => []];

        /** @var ConstraintViolation[] $errors */
        foreach ($errors as $message) {
            $messages['errors'][$message->getPropertyPath()][] = $message->getMessage();
        }

        if (count($messages['errors']) > 0) {
            $response = new JsonResponse($messages, Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->send();
            exit;
        }
    }

    /**
     * @throws DomainException
     */
    protected function populate(): void
    {
        foreach ($this->requestData as $property => $value) {
            if (!property_exists($this, $property)) {
                throw DomainException::propertyNotFound(get_class($this), $property);
            }
            $this->{$property} = $value;
        }
    }

    protected function autoValidateRequest(): bool
    {
        return true;
    }
}