<?php

namespace App\Other\Constraint\Violation;

use App\Other\ArrayList;
use App\Other\Constraint\Core\Violation;
use App\Other\Constraint\Core\ViolationList;
use App\Other\Constraint\Exception\UnknownViolationException;
use JsonSerializable;

/**
 * @extends ArrayList<Violation>
 */
class Violations extends ArrayList implements ViolationList, JsonSerializable
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

    public function containsAny(): bool
    {
        return $this->size() > 0;
    }

    public function isEmpty(): bool
    {
        return !$this->containsAny();
    }

    public function get(
        string  $name,
        ?string $violated = null
    ): ?Violation
    {
        if ($violated === null) {
            foreach ($this->violations as $violation) {
                if ($violation->getName() === $name) {
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

    public function set(Violation $violation): static
    {
        $subject = $this->get($violation->getName(), $violation->getViolated());

        if ($subject === null) {
            throw new UnknownViolationException();
        }

        $subject->setMessage($violation->getMessage());
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

    public function contains(string $name): bool
    {
        return $this->first($name) !== null;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}