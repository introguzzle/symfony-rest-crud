<?php

namespace App\Other\Constraint\Core;

use App\Other\Constraint\Violation\Violation;
use Closure;

interface Validator
{
    public function validate(): ViolationList;
    public function getViolations(): ViolationList;
    public function add(Violation $violation): static;
    public function addAll(ViolationList $violations): static;
    public function remove(string $name): static;
    public function with(self $validator): static;

    /**
     * @param Closure(self): void $closure
     * @return $this
     */
    public function after(Closure $closure): static;

    /**
     * @param Closure(self): void $closure
     * @return $this
     */
    public function before(Closure $closure): static;
}