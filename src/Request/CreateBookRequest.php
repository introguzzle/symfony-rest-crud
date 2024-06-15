<?php

namespace App\Request;

class CreateBookRequest extends AuthorizedRequest
{

    public function getValidationProperties(): array
    {
        return [
            'title'  => 'required, string, unique:books/title',
            'author' => 'required, string',
        ];
    }
}