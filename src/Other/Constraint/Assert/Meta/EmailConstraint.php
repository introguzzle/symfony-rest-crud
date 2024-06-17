<?php

namespace App\Other\Constraint\Assert\Meta;

use App\Other\Constraint\Assert\Core\AbstractConstraint;
use App\Request\Core\Request;

class EmailConstraint extends AbstractConstraint
{
    private string $message;

    public function __construct(
        string $definition,
        string $message = 'Invalid email address.'
    )
    {
        parent::__construct($definition);
        $this->message = $message;
    }

    public function test(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}