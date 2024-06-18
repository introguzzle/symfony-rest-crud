<?php

namespace App\Other\Constraint\Validation;

use App\Log\Log;
use App\Other\Constraint\Assert\Core\Constraint;
use App\Other\Constraint\Assert\Null\NullableConstraint;
use App\Other\Constraint\Core\Resolver;
use App\Other\Constraint\Core\ViolationList;
use App\Other\Constraint\Exception\InvalidFormatException;
use App\Other\Constraint\Group\DefinitionGroup;
use App\Other\Constraint\Group\InitialGroup;
use App\Other\Constraint\Violation\Violation;
use App\Other\Constraint\Violation\Violations;
use InvalidArgumentException;

class PropertyValidator extends AbstractValidator
{
    public const string NULLABLE_DEFINITION = 'nullable';

    protected Resolver $resolver;
    protected string $property;
    protected mixed $value;

    /**
     * @var Constraint[]
     */
    protected array $constraints;
    protected bool $public = true;

    /**
     * @param Resolver $resolver
     * @param string $property
     * @param mixed $value
     * @param string|Constraint|Constraint[] $constraints
     */
    public function __construct(
        Resolver     $resolver,
        string       $property,
        mixed        $value,
        string|array $constraints
    )
    {
        $this->resolver    = $resolver;
        $this->property    = $property;
        $this->value       = $value;
        $this->constraints = $this->resolve($constraints);
    }

    public function validate(): ViolationList
    {
        $this->violations = $this->getViolations();

        foreach ($this->constraints as $constraint) {
            if ($constraint instanceof NullableConstraint && $this->value === null) {
                $this->violations->clear();
                break;
            }

            if (!$constraint->test($this->value)) {
                $violation = new Violation(
                    $this->property,
                    $constraint->getDefinition(),
                    $constraint->getMessage()
                );

                $this->violations->add($violation);
            }
        }

        return $this->violations;
    }

    /**
     * @param Constraint|array<Constraint|string>|string $constraints
     * @return Constraint[]
     */
    public function resolve(Constraint|array|string $constraints): array
    {
        if ($constraints instanceof Constraint) {
            return [$constraints];
        }

        if (is_array($constraints)) {
            foreach ($constraints as $key => $constraint) {
                if (is_string($constraint)) {
                    $constraints[$key] = $this->resolver->resolve(new DefinitionGroup($constraint, ':'));
                }
            }

            return $constraints;
        }

        if (is_string($constraints)) {
            $group = new InitialGroup($constraints);
            $result = [];

            if ($group->hidden()) {
                $this->public = false;
            }

            foreach ($group->split(':') as $definition) {
                $result[] = $this->resolver->resolve($definition);
            }

            return $result;
        }

        if (is_string($constraints)) {
            throw new InvalidFormatException($constraints);
        }

        throw new InvalidArgumentException();
    }
}