<?php

namespace App\Other\Constraint\Validation;

use App\Other\Constraint\Core\Resolver;
use App\Other\Constraint\Violation\Violations;
use App\Request\Core\Request;

class RequestValidator extends AbstractValidator
{
    protected Request $request;
    /**
     * @var PropertyValidator[]
     */
    protected array $propertyValidators = [];
    protected Resolver $resolver;

    /**
     * @param Request $request
     * @param Resolver $resolver
     */
    public function __construct(
        Request $request,
        Resolver $resolver
    )
    {
        $this->request = $request;
        $this->resolver = $resolver;
        $this->build($request->getValidationProperties());
    }

    public function validate(): Violations
    {
        $this->violations ??= new Violations();

        foreach ($this->propertyValidators as $propertyValidator) {
            $this->violations->addAll($propertyValidator->validate());
        }

        return $this->violations;
    }

    public function getViolations(): Violations
    {
        return $this->violations;
    }

    /**
     * @param bool[] $booleans
     * @return bool
     */
    protected function aggregate(array $booleans): bool
    {
        $result = true;

        foreach ($booleans as $boolean) {
            $result &= $boolean;
        }

        return (bool) $result;
    }

    protected function build(array $validationProperties): void
    {
        foreach ($validationProperties as $property => $constraints) {
            $propertyValidator = new PropertyValidator(
                $this->resolver,
                $property,
                $this->request->get($property),
                $constraints
            );

            $this->propertyValidators[] = $propertyValidator;
        }
    }
}