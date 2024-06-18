<?php

namespace App\Other;

class FetchMode
{
    private const int SINGLE = 1;
    private const int COLLECTION = 2;
    private int $mode;

    private function __construct(int $mode)
    {
        $this->mode = $mode;
    }

    public static function single(): static
    {
        return new static(self::SINGLE);
    }

    public static function collection(): static
    {
        return new static(self::COLLECTION);
    }

    public function isSingle(): bool
    {
        return $this->mode === self::SINGLE;
    }

    public function isCollection(): bool
    {
        return $this->mode === self::COLLECTION;
    }
}