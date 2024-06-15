<?php

namespace App\Other\Constraint\Assert\Length;

use App\Other\Constraint\Assert\Core\AbstractConstraint;
use App\Request\Core\Request;

abstract class LengthConstraint extends AbstractConstraint
{
    protected int $value;
    protected ?string $property;
    protected string $message;

    public function __construct(
        Request $request,
        int     $value,
        ?string $property = null,
        string  $message = '',
    )
    {
        parent::__construct($request);
        $this->value = $value;
        $this->property = $property;
        $this->message = $message;
    }

    abstract public function getComparator(): int;

    public function test(mixed $value): bool
    {
        return strlen((string) $value) * $this->getComparator() >= $this->value * $this->getComparator();
    }

    public function getMessage(): string
    {
        $this->message = str_replace('{{ limit }}', (string) $this->value, $this->message);
        if ($this->property !== null) {
            $this->message = str_replace('This field', '{{ property }}', $this->message);
            return str_replace('{{ property }}', $this->property, $this->message);
        }

        return $this->message;
    }
}