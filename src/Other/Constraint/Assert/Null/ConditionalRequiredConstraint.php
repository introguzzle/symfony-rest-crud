<?php

namespace App\Other\Constraint\Assert\Null;

use App\Request\Core\Request;

class ConditionalRequiredConstraint extends RequiredConstraint
{
    private mixed $other;

    public function __construct(
        string $definition,
        mixed  $otherValue,
        string $message = 'This field cannot be null.',

    )
    {
        parent::__construct($definition, $message);
        $this->other = $otherValue;
    }

    public function test(mixed $value): bool
    {
        if ($this->other === null) {
            return true;
        }

        return parent::test($value);
    }
}