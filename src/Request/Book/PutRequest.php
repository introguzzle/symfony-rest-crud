<?php

namespace App\Request\Book;

use App\Log\Log;
use App\Other\FetchMode;
use App\Other\Options;
use App\Other\ValidationProperties;

/**
 * @property int $id
 * @property string $title
 * @property string $author
 */
class PutRequest extends Request
{
    public function getValidationProperties(): ValidationProperties
    {
        return new ValidationProperties([
            'id'     => 'required, exists:books/id',
            'title'  => 'required, string',
            'author' => 'required, string',
        ]);
    }

    public function getEntityOptions(): Options
    {
        return new Options(FetchMode::single(), ['id']);
    }
}