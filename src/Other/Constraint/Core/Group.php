<?php

namespace App\Other\Constraint\Core;

use App\Other\Constraint\UnknownConstraintException;

interface Group
{
    public function normalize(): static;

    /**
     * @param string $then
     * @return static[]
     */
    public function split(string $then): array;

    /**
     * @return string[]
     */
    public function divide(): array;

    public function contains(string $needle): bool;

    /**
     * @throws UnknownConstraintException
     * @return bool
     */
    public function exists(): bool;

    /**
     * @return class-string
     */
    public function retrieve(): string;
    public function getContent(): string;
}