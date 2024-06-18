<?php

namespace App\Request\Book;

use App\Other\FetchMode;
use App\Other\Options;
use App\Other\ValidationProperties;

/**
 * @property int $id
 */
class DeleteRequest extends Request
{
    public function getValidationProperties(): ValidationProperties
    {
        return new ValidationProperties([
            'id' => 'required, exists:books/id',
        ]);
    }

    public function getEntityOptions(): Options
    {
        return new Options(FetchMode::single(), ['id']);
    }
}