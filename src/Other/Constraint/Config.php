<?php

namespace App\Other\Constraint;

use App\Other\Constraint\Assert\Core\NullConstraint;
use App\Other\Constraint\Assert\Length\DefaultLowerLengthConstraint;
use App\Other\Constraint\Assert\Length\DefaultUpperLengthConstraint;
use App\Other\Constraint\Assert\Meta\EmailConstraint;
use App\Other\Constraint\Assert\Meta\PhoneConstraint;
use App\Other\Constraint\Assert\Null\RequiredConstraint;
use App\Other\Constraint\Assert\Type\DecimalConstraint;
use App\Other\Constraint\Assert\Type\IntegerConstraint;
use App\Other\Constraint\Assert\Type\NumericConstraint;
use App\Other\Constraint\Assert\Type\StringConstraint;

class Config
{
    /**
     * @var array<string, class-string>|class-string[]
     */
    public array $map = [
        'required' => RequiredConstraint::class,
        'email'    => EmailConstraint::class,
        'phone'    => PhoneConstraint::class,
        'numeric'  => NumericConstraint::class,
        'string'   => StringConstraint::class,
        'int'      => IntegerConstraint::class,
        'float'    => DecimalConstraint::class,
        'double'   => DecimalConstraint::class,
        'decimal'  => DecimalConstraint::class,
        'hidden'   => NullConstraint::class,
        'min'      => DefaultLowerLengthConstraint::class,
        'max'      => DefaultUpperLengthConstraint::class,
    ];

    private static self $instance;

    private function __construct()
    {

    }

    public static function getInstance(): static
    {
        return self::$instance ?? self::$instance = new static();
    }
}