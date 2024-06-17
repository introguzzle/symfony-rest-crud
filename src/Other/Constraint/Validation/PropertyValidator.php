<?php

namespace App\Other\Constraint\Validation;

use App\Other\Constraint\Assert\Core\Constraint;
use App\Other\Constraint\Core\Resolver;
use App\Other\Constraint\Group\DefinitionGroup;
use App\Other\Constraint\Group\InitialGroup;
use App\Other\Constraint\Violation\Violation;
use App\Other\Constraint\Violation\Violations;
use InvalidArgumentException;

class PropertyValidator extends AbstractValidator
{
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
        $this->resolver = $resolver;
        $this->property = $property;
        $this->value    = $value;
        $this->constraints = $this->resolve($constraints);
    }

    public function validate(): Violations
    {
        $this->violations ??= new Violations();

        foreach ($this->constraints as $constraint) {
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

        throw new InvalidArgumentException('Invalid format');
    }
}