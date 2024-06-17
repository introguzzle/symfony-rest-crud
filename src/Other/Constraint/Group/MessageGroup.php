<?php

namespace App\Other\Constraint\Group;

use App\Other\Constraint\Config;

class MessageGroup extends AbstractGroup
{
    public function __construct(string $string)
    {
        parent::__construct($string, '.');
    }

    public function exists(): bool
    {
        [, $violation] = $this->divide();
        return array_key_exists($violation, Config::getInstance()->map);
    }
}