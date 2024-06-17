<?php

namespace App\Other\Constraint\Assert;

use App\Other\Constraint\Assert\Core\AbstractConstraint;
use Closure;

class RequestConstraint extends AbstractConstraint
{
    private Closure $callback;
    private string $message;

    /**
     * @param string $definition
     * @param Closure(mixed): bool $callback
     * @param string $message
     */
    public function __construct(
        string  $definition,
        Closure $callback,
        string  $message = 'Validation failed'
    )
    {
        parent::__construct($definition);
        $this->callback = $callback;
        $this->message = $message;
    }

    public function test(mixed $value): bool
    {
        return call_user_func($this->callback, $value);
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}