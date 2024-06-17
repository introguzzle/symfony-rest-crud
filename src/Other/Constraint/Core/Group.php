<?php

namespace App\Other\Constraint\Core;

use App\Other\Constraint\Exception\UnknownConstraintException;

interface Group
{
    /**
     * Normalizes the content of the group.
     *
     * @return static
     */
    public function normalize(): static;

    /**
     * Splits the content of the group by the specified delimiter.
     *
     * @param string $then The delimiter used for splitting.
     * @return static[] An array of split group instances.
     */
    public function split(string $then): array;

    /**
     * Divides the content of the group into an array of strings.
     *
     * @return string[] An array of divided strings.
     */
    public function divide(): array;

    /**
     * Checks if the content of the group contains the specified needle.
     *
     * @param string $needle The string to search for within the content.
     * @return bool True if the content contains the needle, false otherwise.
     */
    public function contains(string $needle): bool;

    /**
     * Checks if the content of the group exists as a known constraint.
     *
     * @throws UnknownConstraintException If the constraint is unknown.
     * @return bool True if the constraint exists, false otherwise.
     */
    public function exists(): bool;

    /**
     * Retrieves the class name of the constraint associated with the content.
     *
     * @return class-string The class name of the constraint.
     */
    public function retrieve(): string;

    /**
     * Gets the content of the group as a string.
     *
     * @return string The content of the group.
     */
    public function getContent(): string;
}