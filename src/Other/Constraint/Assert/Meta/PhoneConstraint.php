<?php

namespace App\Other\Constraint\Assert\Meta;


use App\Other\Constraint\Assert\Core\AbstractConstraint;

class PhoneConstraint extends AbstractConstraint
{
    public function test(mixed $value): bool
    {
        return is_int($value) && filter_var($value, FILTER_VALIDATE_INT);
    }

    public function getMessage(): string
    {
        return 'Invalid phone number';
    }
}