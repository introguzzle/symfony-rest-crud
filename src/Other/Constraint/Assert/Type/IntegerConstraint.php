<?php

namespace App\Other\Constraint\Assert\Type;

use App\Other\Constraint\Assert\Core\TypeConstraint;

class IntegerConstraint extends TypeConstraint
{

    public function test(mixed $value): bool
    {
        return is_int((int) $value);
    }

    public function getMessage(): string
    {
        return 'This field must be a number.';
    }
}