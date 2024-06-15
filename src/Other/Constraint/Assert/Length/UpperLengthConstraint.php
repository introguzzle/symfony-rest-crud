<?php

namespace App\Other\Constraint\Assert\Length;

use App\Request\Core\Request;

class UpperLengthConstraint extends LengthConstraint
{
    public function __construct(
        Request $request,
        int     $value,
        ?string $property = null,
        string  $message = 'This field must be shorter than {{ limit }} characters.'
    )
    {
        parent::__construct($request, $value, $property, $message);
    }

    public function getComparator(): int
    {
        return -1;
    }
}