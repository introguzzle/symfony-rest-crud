<?php

namespace App\Other\Constraint\Group;

use App\Other\Constraint\Config;
use App\Other\Constraint\Core\Group;
use Stringable;

abstract class AbstractGroup implements Group, Stringable
{
    protected string $string;
    protected string $delimiter;

    public function __construct(
        string $string,
        string $delimiter
    )
    {
        $this->string = $string;
        $this->delimiter = $delimiter;

        $this->normalize();
    }

    public function normalize(): static
    {
        $this->string = strtolower(trim(str_replace(' ', '', $this->string)));
        return $this;
    }

    /**
     * @return static[]
     */
    public function split(string $then): array
    {
        $static = [];

        foreach (explode($this->delimiter, $this->string) as $part) {
            $static[] = new static($part, $then);
        }

        return $static;
    }

    public function divide(): array
    {
        return explode($this->delimiter, $this->string);
    }

    /**
     * @return bool
     */

    public function exists(): bool
    {
        return array_key_exists($this->string, Config::getInstance()->map);
    }

    public function getContent(): string
    {
        return $this->string;
    }

    public function contains(string $needle): bool
    {
        return str_contains($this->string, $needle);
    }

    public function retrieve(): string
    {
        return Config::getInstance()->map[$this->string];
    }

    public function __toString(): string
    {
        return $this->string;
    }
}