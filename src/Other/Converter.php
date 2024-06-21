<?php

namespace App\Other;

/**
 * @template T
 * @template R
 */
interface Converter
{
    /**
     * @param T $value
     * @return R
     */
    public function convert($value);
}