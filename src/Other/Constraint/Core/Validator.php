<?php

namespace App\Other\Constraint\Core;

use App\Other\Constraint\Violation\Violation;
use App\Other\Constraint\Violation\Violations;
use Closure;

interface Validator
{
    public function validate(): Violations;
    public function getViolations(): Violations;
    public function add(Violation $violation): static;
    public function addAll(Violations $violations): static;
    public function with(self $validator): static;

    /**
     * @param Closure(self): void $closure
     * @return $this
     */
    public function after(Closure $closure): static;
}