<?php

namespace App\Other\Constraint\Exception;

use RuntimeException;
use Throwable;

class InvalidFormatException extends RuntimeException
{
    public function __construct(
        string $content,
    )
    {
        parent::__construct($content);
    }
}