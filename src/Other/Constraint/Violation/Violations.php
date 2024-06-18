<?php

namespace App\Other\Constraint\Violation;

use App\Other\ArrayList;
use App\Other\Constraint\Core\Violation;
use App\Other\Constraint\Core\ViolationList;
use App\Other\Constraint\Exception\UnknownViolationException;

/**
 * @extends ArrayList<Violation>
 */
class Violations extends ArrayList implements ViolationList
{
    protected array $violations = [];

    /**
     * @param Violation[] $violations
     */
    public function __construct(array $violations = [])
    {
        $this->violations = $violations;
    }
    
    public function add(Violation $violation): static
    {
        $this->violations[] = $violation;
        return $this;
    }

    public function addAll(ViolationList $violations): static
    {
        $this->violations = array_merge($this->violations, $violations->all());
        return $this;
    }

    public function hasAny(): bool
    {
        return $this->size() > 0;
    }

    public function isEmpty(): bool
    {
        return !$this->hasAny();
    }

    public function get(
        string  $name,
        ?string $violated = null
    ): ?Violation
    {
        if ($violated === null) {
            foreach ($this->violations as $violation) {
                if ($violation->getViolated() === $name) {
                    return $violation;
                }
            }
        }

        foreach ($this->violations as $violation) {
            if ($violation->getName() === $name && $violation->getViolated() === $violated) {
                return $violation;
            }
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        $violations = [];

        foreach ($this->violations as $violation) {
            $name = $violation->getName();
            $violations[$name] = $this->first($name)?->getMessage() ?? $violation->getMessage();
        }

        return $violations;
    }

    public function first(string $name): ?Violation
    {
        return $this->get($name);
    }

    public function all(): array
    {
        return $this->violations;
    }

    public function set(string $name, string $violated, string $message): static
    {
        $violation = $this->get($name, $violated);

        if ($violation === null) {
            throw new UnknownViolationException();
        }

        $violation->setMessage($message);
        return $this;
    }

    public function remove(string $name): static
    {
        foreach ($this->violations as $key => $violation) {
            if ($violation->getName() === $name) {
                unset($this->violations[$key]);
            }
        }

        return $this;
    }

    public function clear(): static
    {
        $this->violations = [];
        return $this;
    }

    public function has(string $name): bool
    {
        return $this->first($name) !== null;
    }
}