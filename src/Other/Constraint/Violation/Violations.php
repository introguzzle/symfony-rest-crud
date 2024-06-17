<?php

namespace App\Other\Constraint\Violation;

use App\Log\Log;
use App\Other\ArrayList;
use App\Other\Constraint\Exception\UnknownViolationException;
use App\Other\Constraint\Group\MessageGroup;

/**
 * @extends ArrayList<Violation>
 */
class Violations extends ArrayList
{
    protected array $violations = [];
    /**
     * @param Violation[] $violations
     */
    public function __construct(array $violations = [])
    {
        $this->violations = $violations;
    }

    public function any(): Violation
    {
        return array_values($this->violations)[0];
    }

    public function add(Violation $violation): static
    {
        $this->violations[] = $violation;
        return $this;
    }

    public function addAll(self $violations): static
    {
        $this->violations = array_merge($this->violations, $violations->all());
        return $this;
    }

    public function hasAny(): bool
    {
        return $this->size() > 0;
    }

    public function hasNone(): bool
    {
        return !$this->hasAny();
    }

    public function get(
        string  $name,
        ?string $violated = null
    ): ?Violation
    {
        foreach ($this->violations as $violation) {
            if ($violated === null && $violation->getName() === $name) {
                return $violation;
            }

            if ($violation->getName() === $name && $violation->getViolated() === $violated) {
                return $violation;
            }
        }

        return null;
    }

    /**
     * @return array
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
}