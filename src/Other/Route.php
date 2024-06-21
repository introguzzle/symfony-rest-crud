<?php

namespace App\Other;

class Route
{
    protected string $name;
    protected string $class;
    protected string $method;

    /**
     * @param string $name
     * @param class-string $class
     */
    public function __construct(string $name, string $class)
    {
        $this->name = $name;
        [$this->class, $this->method] = explode('::', $class);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return class-string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    public function getClassMethod(): string
    {
        return $this->method;
    }
}