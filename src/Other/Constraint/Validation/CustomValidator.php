<?php

namespace App\Other\Constraint\Validation;

use App\Other\Constraint\Assert\Core\Constraint;
use App\Other\Constraint\Core\Resolver;
use App\Request\Core\Request;

class CustomValidator extends RequestValidator
{
    /**
     * @param Request $request
     * @param Resolver $resolver
     * @param array<string, string|Constraint|Constraint[]> $definition
     */
    public function __construct(
        Request  $request,
        Resolver $resolver,
        array    $definition,
    )
    {
        parent::__construct($request, $resolver);
        $this->build($definition);
    }
}