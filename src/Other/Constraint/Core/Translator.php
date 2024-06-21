<?php

namespace App\Other\Constraint\Core;

interface Translator
{
    public function translate(string $string): string;
}