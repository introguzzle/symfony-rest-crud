<?php

namespace App\Request\Book;

use App\Entity\Book;
use App\Request\Entity\Request as EntityRequest;

/**
 * @extends EntityRequest<Book>
 */
abstract class Request extends EntityRequest
{
    public function getEntityClass(): string
    {
        return Book::class;
    }
}