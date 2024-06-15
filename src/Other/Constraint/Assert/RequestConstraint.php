<?php

namespace App\Other\Constraint\Assert;

use App\Other\Constraint\Assert\Core\AbstractConstraint;
use App\Request\Core\Request;
use Closure;

class RequestConstraint extends AbstractConstraint
{
    private Closure $callback;
    private string $message;

    /**
     * @param Request $request
     * @param Closure(Request, mixed): bool $callback
     * @param string $message
     */
    public function __construct(
        Request $request,
        Closure $callback,
        string $message = 'Validation failed'
    )
    {
        parent::__construct($request);
        $this->callback = $callback;
        $this->message = $message;
    }

    public function test(mixed $value): bool
    {
        return call_user_func($this->callback, $this->request, $value);
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}