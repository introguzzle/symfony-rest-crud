<?php

namespace App\Other\Constraint\Core;


use App\Other\Constraint\Assert\Core\Constraint;

interface Resolver
{
    public function resolve(Group $group): Constraint;
}