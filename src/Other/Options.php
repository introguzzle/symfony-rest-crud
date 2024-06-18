<?php

namespace App\Other;

class Options
{
    private ?FetchMode $fetchMode;
    private array $properties;

    public function __construct(
        ?FetchMode $fetchMode = null,
        array      $properties = [],
    )
    {
        $this->fetchMode = $fetchMode;
        $this->properties = $properties;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getFetchMode(): ?FetchMode
    {
        return $this->fetchMode;
    }
}