<?php

namespace App\Other\Constraint\Validation;

use App\Other\Constraint\Core\Validator;
use App\Other\Constraint\Core\Violation;
use App\Other\Constraint\Core\ViolationList;
use App\Other\Constraint\Violation\Violations;
use Closure;

abstract class AbstractValidator implements Validator
{
    protected ViolationList $violations;
    public function add(Violation $violation): static
    {
        $this->getViolations()->add($violation);
        return $this;
    }

    public function addAll(ViolationList $violations): static
    {
        $this->getViolations()->addAll($violations);
        return $this;
    }

    public function remove(string $name): static
    {
        $this->violations->remove($name);
        return $this;
    }

    public function after(Closure $closure): static
    {
        $this->validate();
        $closure($this);
        return $this;
    }

    public function before(Closure $closure): static
    {
        $closure($this);
        $this->validate();
        return $this;
    }

    public function with(Validator $validator): static
    {
        return $this->addAll($validator->validate());
    }

    public function getViolations(): ViolationList
    {
        if (!isset($this->violations)) {
            return $this->violations = new Violations();
        }

        return $this->violations;
    }
}