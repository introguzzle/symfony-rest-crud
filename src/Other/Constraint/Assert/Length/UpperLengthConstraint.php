<?php

namespace App\Other\Constraint\Assert\Length;

use App\Request\Core\Request;

class UpperLengthConstraint extends LengthConstraint
{
    public function __construct(
        string  $definition,
        int     $value,
        ?string $property = null,
        string  $message = 'This field must be shorter than {{ limit }} characters.'
    )
    {
        parent::__construct($definition, $value, $property, $message);
    }

    public function getComparator(): int
    {
        return -1;
    }
}