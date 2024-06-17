<?php

namespace App\Request\Core;

use App\Entity\User;

abstract class AuthorizedRequest extends Request
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