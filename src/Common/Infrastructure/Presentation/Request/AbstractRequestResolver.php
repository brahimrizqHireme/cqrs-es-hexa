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

    abstract protected function toArray(): array;

    /**
     * @throws DomainException
     * @throws \ReflectionException
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


//            $reflection = new ReflectionClass($class);
//            $attributes = $reflection->getAttributes();
//
//            $commandInstance = null;
//            foreach ($attributes as $attribute) {
//                $instance = $attribute->newInstance();
//                if ($instance instanceof CommandAttributeResolver) {
//                    $commandInstance = call_user_func_array([$instance->getCommand(), 'withData'], $requestData);
//                    break;
//                }
//            }
//
//            if (empty($attributes) || !$commandInstance instanceof CommandInterface) {
//                throw DomainException::withCommandArgumentResolver();
//            }
//
//            yield $commandInstance;
        }
    }

    public function validate(): void
    {
        $errors = $this->validator->validate($this);
        $messages = ['message' => 'validation_failed', 'code' => Response::HTTP_BAD_REQUEST, 'errors' => []];

        /** @var ConstraintViolation[] $errors */
        foreach ($errors as $message) {
            $messages['errors'][$message->getPropertyPath()][] = $message->getMessage();
        }

        if (count($messages['errors']) > 0) {
            $response = new JsonResponse($messages, Response::HTTP_BAD_REQUEST);
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