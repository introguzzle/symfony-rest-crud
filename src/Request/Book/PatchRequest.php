<?php

namespace App\Request\Book;

use App\Log\Log;
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
            'title'  => 'nullable, string, notblank',
            'author' => 'nullable, string, notblank',
        ]);
    }

    public function getEntityOptions(): Options
    {
        return new Options(FetchMode::single(), ['id']);
    }
}