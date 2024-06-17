<?php

namespace App\Other\Constraint\Assert\Null;

use App\Other\Constraint\Assert\Core\AbstractConstraint;
use App\Request\Core\Request;

class NotBlankConstraint extends AbstractConstraint
{
    private string $message;

    public function __construct(
        string $definition,
        string $message = 'This field cannot be blank.'
    )
    {
        parent::__construct($definition);
        $this->message = $message;
    }

    public function test(mixed $value): bool
    {
        return !empty($value);
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}