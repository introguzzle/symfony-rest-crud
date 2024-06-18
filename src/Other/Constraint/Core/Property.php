<?php

namespace App\Other\Constraint\Core;

use Stringable;

interface Property extends Stringable
{
    public function getName(): string;

    public function getValue(): mixed;
}