<?php

namespace App\Request;

use App\Entity\User;
use App\Request\Core\Request;

class AuthorizedRequest extends Request
{
    public function retrieveUser(): User
    {
        return parent::retrieveUser();
    }

    public function getValidationProperties(): array
    {
        return [];
    }
}