<?php

namespace App\Other\Constraint\Assert\Core;

class NullConstraint extends AbstractConstraint
{
    public function test(mixed $value): bool
    {
        return true;
    }

    public function getMessage(): string
    {
        return '';
    }
}