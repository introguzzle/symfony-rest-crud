<?php

namespace App\Request\Book;

use App\Other\FetchMode;
use App\Other\Options;
use App\Other\ValidationProperties;

/**
 * @property int $id
 * @property ?string $title
 * @property ?string $author
 */
class PatchRequest extends Request
{
    public function getValidationProperties(): ValidationProperties
    {
        return new ValidationProperties([
            'id'     => 'required, exists:books/id',
            'title'  => 'nullable, string, notBlank',
            'author' => 'nullable, string, notBlank',
        ]);
    }

    public function getEntityOptions(): Options
    {
        return new Options(FetchMode::single(), ['id']);
    }
}