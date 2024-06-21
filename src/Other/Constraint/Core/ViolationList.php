<?php

namespace App\Other\Constraint\Core;

interface ViolationList
{
    public function add(Violation $violation): static;

    public function addAll(self $violations): static;

    public function containsAny(): bool;

    public function isEmpty(): bool;

    public function get(string $name, ?string $violated = null): ?Violation;

    /**
     * @return array<string, string>
     */
    public function toArray(): array;

    public function first(string $name): ?Violation;

    public function all(): array;

    public function set(Violation $violation): static;

    public function remove(string $name): static;

    public function clear(): static;

    public function contains(string $name): bool;
    public function size(): int;
}