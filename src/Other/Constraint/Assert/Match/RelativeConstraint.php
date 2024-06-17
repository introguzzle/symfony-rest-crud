<?php

namespace App\Other\Constraint\Assert\Match;

use App\Other\Constraint\Assert\Core\AbstractConstraint;

abstract class RelativeConstraint extends AbstractConstraint
{
    protected mixed $otherValue;
    protected string $otherKey;

    public function __construct(
        string $definition,
        string $otherKey,
        mixed  $otherValue
    )
    {
        parent::__construct($definition);
        $this->otherKey = $otherKey;
        $this->otherValue = $otherValue;
    }
}