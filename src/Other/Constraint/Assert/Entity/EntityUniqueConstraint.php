<?php

namespace App\Other\Constraint\Assert\Entity;

class EntityUniqueConstraint extends EntityExistsConstraint
{
    public function test(mixed $value): bool
    {
        return !parent::test($value);
    }

    public function getMessage(): string
    {
        return 'This field violates unique constraint';
    }
}