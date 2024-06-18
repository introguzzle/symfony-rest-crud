<?php

namespace App\Request\Book;

use App\Other\ValidationProperties;
use App\Request\Core\AuthorizedRequest;

class BookRequest extends AuthorizedRequest
{
    public function getValidationProperties(): ValidationProperties
    {
        return new ValidationProperties();
    }
}