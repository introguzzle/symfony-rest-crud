<?php

namespace App\Other\Constraint\Assert\Core;

use App\Other\Constraint\Errors;

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
     * Определяет, должно ли сообщение об ошибке быть публичным.
     *
     * @return bool Возвращает true, если сообщение должно быть публичным, иначе false.
     */
    public function isPublic(): bool;

    /**
     * Добавляет ошибку в объект Errors, если значение не соответствует условию.
     *
     * @param mixed $value Значение для проверки.
     * @param Errors $errors Объект, в который будут добавляться ошибки.
     * @param string $property Имя свойства, к которому относится ошибка.
     * @return bool Возвращает true, если значение соответствует условию, иначе false.
     */
    public function add(
        mixed  $value,
        Errors $errors,
        string $property,
    ): bool;
}
