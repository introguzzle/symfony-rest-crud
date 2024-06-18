<?php

namespace App\Other;

use App\Other\Constraint\Assert\Core\Constraint;

class ValidationProperties
{
    /**
     * @var array<string, string|Constraint|Constraint[]>
     */
    private array $properties;

    /**
     * @param array<string, string|Constraint|Constraint[]> $properties
     */
    public function __construct(array $properties = [])
    {
        $this->properties = $properties;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }
}