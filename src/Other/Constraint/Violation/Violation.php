<?php

namespace App\Other\Constraint\Violation;

class Violation implements \App\Other\Constraint\Core\Violation
{
    protected string $name;
    protected string $violated;
    protected string $message;

    public function __construct(
        string $name,
        string $violated,
        string $message
    )
    {
        $this->name = $name;
        $this->violated = $violated;
        $this->message = $message;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }


    public function setMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    public function getViolated(): string
    {
        return $this->violated;
    }

    public function setViolated(string $violated): static
    {
        $this->violated = $violated;
        return $this;
    }
}