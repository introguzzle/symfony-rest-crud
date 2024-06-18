<?php

namespace App\Request\Book;

use App\Other\Options;
use App\Other\ValidationProperties;

/**
 * @property string $title
 * @property string $author
 */
class CreateRequest extends Request
{
    public function getValidationProperties(): ValidationProperties
    {
        return new ValidationProperties([
            'title'  => 'required, string, unique:books/title',
            'author' => 'required, string',
        ]);
    }

    public function getEntityOptions(): Options
    {
        return new Options();
    }
}