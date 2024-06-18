<?php

namespace App\Other\Constraint\Core;

interface Config
{
    /**
     * @return class-string[]
     */
    public function getRegisteredConstraints(): array;
}