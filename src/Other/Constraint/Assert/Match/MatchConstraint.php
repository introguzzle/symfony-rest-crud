<?php

namespace App\Other\Constraint\Assert\Match;

use App\Other\Constraint\Assert\Core\AbstractConstraint;
use Doctrine\Inflector\Rules\Pattern;

class MatchConstraint extends AbstractConstraint
{
    protected Pattern $pattern;
    protected string $message;

    public function __construct(
        string  $definition,
        Pattern $pattern,
        string  $message = "This field has invalid value"
    )
    {
        parent::__construct($definition);
        $this->pattern = $pattern;
        $this->message = $message;
    }

    public function test(mixed $value): bool
    {
        return $this->pattern->matches((string) $value);
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}