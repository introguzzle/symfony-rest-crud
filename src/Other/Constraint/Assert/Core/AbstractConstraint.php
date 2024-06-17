<?php

namespace App\Other\Constraint\Assert\Core;

abstract class AbstractConstraint implements Constraint
{
    protected string $definition;
    public function __construct(string $definition)
    {
        $this->definition = $definition;
    }

    public function getDefinition(): string
    {
        return $this->definition;
    }
}