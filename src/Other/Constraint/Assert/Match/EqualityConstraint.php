<?php

namespace App\Other\Constraint\Assert\Match;

class EqualityConstraint extends RelativeConstraint
{
    public function test(mixed $value): bool
    {
        return $this->otherValue === $value;
    }

    public function getMessage(): string
    {
        return "This value should be same as $this->otherKey";
    }
}