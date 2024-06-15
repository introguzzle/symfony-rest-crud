<?php

namespace App\Other\Constraint\Assert\Core;

use App\Other\Constraint\Errors;
use App\Request\Core\Request;

abstract class AbstractConstraint implements Constraint
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function add(
        mixed  $value,
        Errors $errors,
        string $property,
    ): bool
    {
        if (($result = !$this->test($value)) && $this->isPublic()) {
            $errors->set($property, $this->getMessage());
        }

        return !$result;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}