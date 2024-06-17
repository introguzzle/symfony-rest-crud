<?php

namespace App\Other\Constraint\Assert\Entity;

class EntityUniqueConstraint extends EntityExistsConstraint
{
    public function test(mixed $value): bool
    {
        $result = parent::test($value);

        if ($this->failed) {
            return false;
        }

        return !$result;
    }

    public function getMessage(): string
    {
        return 'This field violates unique constraint';
    }
}