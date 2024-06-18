<?php

namespace App\Request\Security;

use App\Other\ValidationProperties;
use App\Request\Core\UnguardedRequest;

class LoginRequest extends UnguardedRequest
{
    public function getValidationProperties(): ValidationProperties
    {
        return new ValidationProperties([
            'login'    => 'required, min:6',
            'password' => 'required, min:6',
        ]);
    }
}