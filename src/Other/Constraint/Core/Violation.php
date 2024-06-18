<?php

namespace App\Other\Constraint\Core;

interface Violation
{
    public function getName(): string;

    public function getMessage(): string;

    public function getViolated(): string;

    public function setName(string $name): static;

    public function setMessage(string $message): static;

    public function setViolated(string $violated): static;
}