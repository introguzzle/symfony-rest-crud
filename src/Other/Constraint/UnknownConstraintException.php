<?php

namespace App\Other\Constraint;

use RuntimeException;

class UnknownConstraintException extends RuntimeException
{
    public function __construct(string $constraint)
    {
        parent::__construct("Unknown constraint: $constraint");
    }
}