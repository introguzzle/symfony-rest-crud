<?php

namespace App\Other\Constraint\Core;

interface Handler
{
    public function handle(array|string $group): Group;
}