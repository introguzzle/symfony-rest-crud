<?php

namespace App\Other\Constraint\Assert\Length;

use App\Request\Core\Request;

class DefaultUpperLengthConstraint extends UpperLengthConstraint
{
    public function __construct(
        Request $request,
        ?string $property = null,
        string $message = 'This field must be shorter than {{ limit }} characters.'
    )
    {
        parent::__construct($request, 255, $property, $message);
    }
}