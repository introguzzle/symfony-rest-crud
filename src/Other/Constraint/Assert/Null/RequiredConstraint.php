<?php

namespace App\Other\Constraint\Assert\Null;

use App\Other\Constraint\Assert\Core\AbstractConstraint;
use App\Request\Core\Request;

class RequiredConstraint extends AbstractConstraint
{
    private string $message;

    public function __construct(
        string  $definition,
        string  $message = 'This field cannot be null.'
    )
    {
        parent::__construct($definition);
        $this->message = $message;
    }

    public function test(mixed $value): bool
    {
        return $value !== null;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}