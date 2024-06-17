<?php

namespace App\Other\Constraint\Assert\Core;

interface Constraint
{
    /**
     * Проверяет, соответствует ли значение указанному условию.
     *
     * @param mixed $value Значение для проверки.
     * @return bool Возвращает true, если значение соответствует условию, иначе false.
     */
    public function test(mixed $value): bool;

    /**
     * Получает сообщение об ошибке, если значение не соответствует условию.
     *
     * @return string Сообщение об ошибке.
     */
    public function getMessage(): string;

    /**
     * @return string
     */
    public function getDefinition(): string;
}
