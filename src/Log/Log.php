<?php

namespace App\Log;

class Log
{
    public static function log(mixed $message): void
    {
        $formattedMessage = static::formatMessage($message);
        file_put_contents('.././log.log', $formattedMessage . PHP_EOL, FILE_APPEND);
    }

    public static function print(mixed $message): void
    {
        static::log(print_r($message, true));
    }

    private static function formatMessage(mixed $message): string
    {
        if (is_array($message)) {
            return implode(', ', array_values($message));
        }

        return (string) $message;
    }
}