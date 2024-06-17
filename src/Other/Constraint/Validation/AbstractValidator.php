<?php

namespace App\Other\Constraint\Validation;

use App\Other\Constraint\Core\Validator;
use App\Other\Constraint\Violation\Violation;
use App\Other\Constraint\Violation\Violations;
use Closure;

abstract class AbstractValidator implements Validator
{
    protected Violations $violations;
    public function add(Violation $violation): static
    {
        $this->getViolations()->add($violation);
        return $this;
    }

    public function addAll(Violations $violations): static
    {
        $this->getViolations()->addAll($violations);
        return $this;
    }

    public function after(Closure $closure): static
    {
        $this->validate();
        $closure($this);
        return $this;
    }

    public function with(Validator $validator): static
    {
        return $this->addAll($validator->validate());
    }

    public function getViolations(): Violations
    {
        if (!isset($this->violations)) {
            return $this->violations = new Violations();
        }

        return $this->violations;
    }
}