<?php

namespace App\Request;

use App\Request\Core\UnguardedRequest;

class LoginRequest extends UnguardedRequest
{
    public function getValidationProperties(): array
    {
        return [
            'login'    => 'required, min:6',
            'password' => 'required, min:6',
        ];
    }
}