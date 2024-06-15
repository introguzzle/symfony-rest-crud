<?php

namespace App\Other\Constraint\Assert\Length;

use App\Request\Core\Request;

class LowerLengthConstraint extends LengthConstraint
{
    public function __construct(
        Request $request,
        int     $value,
        ?string $property = null,
        string  $message = 'This field must be at least {{ limit }} characters.'
    )
    {
        parent::__construct($request, $value, $property, $message);
    }

    public function getComparator(): int
    {
        return 1;
    }
}