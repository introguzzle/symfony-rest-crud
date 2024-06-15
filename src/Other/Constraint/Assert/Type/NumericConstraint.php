<?php

namespace App\Other\Constraint\Assert\Type;

use App\Other\Constraint\Assert\Core\AbstractConstraint;

class NumericConstraint extends AbstractConstraint
{

    public function test(mixed $value): bool
    {
        return is_numeric($value);
    }

    public function getMessage(): string
    {
        return 'This field must be a number.';
    }
}