<?php

namespace App\Other\Constraint\Assert\Length;

use App\Request\Core\Request;

class LowerLengthConstraint extends LengthConstraint
{
    public function __construct(
        string  $definition,
        int     $value,
        ?string $property = null,
        string  $message = 'This field must be at least {{ limit }} characters.'
    )
    {
        parent::__construct($definition, $value, $property, $message);
    }

    public function getComparator(): int
    {
        return 1;
    }
}