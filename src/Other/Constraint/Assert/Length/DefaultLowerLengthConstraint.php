<?php

namespace App\Other\Constraint\Assert\Length;

use App\Request\Core\Request;

class DefaultLowerLengthConstraint extends LowerLengthConstraint
{
    public function __construct(
        Request $request,
        ?string $property = null,
        string $message = 'This field must be at least {{ limit }} characters.'
    )
    {
        parent::__construct($request, 6, $property, $message);
    }
}