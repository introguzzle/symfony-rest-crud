<?php

namespace App\Other\Constraint\Assert\Length;

use App\Request\Core\Request;

class DefaultUpperLengthConstraint extends UpperLengthConstraint
{
    public function __construct(
        string $definition,
        ?string $property = null,
        string $message = 'This field must be shorter than {{ limit }} characters.'
    )
    {
        parent::__construct($definition, 255, $property, $message);
    }
}