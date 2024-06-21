<?php

namespace App\Other;

class Environment
{
    protected ?string $path;
    private static array $cache;
    private static string $content;

    public function __construct(?string $path = null)
    {
        $this->path = $path ?? './../.env';
    }

    public function get(string $key): ?string
    {
        if (! isset(static::$cache)) {
            static::$cache = [];
        }

        if (array_key_exists($key, static::$cache)) {
            return static::$cache[$key];
        }

        if (! isset(static::$content)) {
            static::$content = file_get_contents('./../.env');
        }

        $lines = explode(PHP_EOL, static::$content);

        foreach ($lines as $line) {
            $line = trim($line);
            if (str_contains($line, $key)) {
                [, $value] = explode('=', $line);

                $trim = trim($value);
                static::$cache[$key] = $trim;
                return $trim;
            }
        }

        return null;
    }
}