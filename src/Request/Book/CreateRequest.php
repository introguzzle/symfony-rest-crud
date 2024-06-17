<?php

namespace App\Request\Book;

class CreateRequest extends Request
{

    public function getValidationProperties(): array
    {
        return [
            'title'  => 'required, string, unique:books/title',
            'author' => 'required, string',
        ];
    }

    public function prepare(): void
    {

    }
}