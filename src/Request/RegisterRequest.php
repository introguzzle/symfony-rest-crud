<?php

namespace App\Request;

use App\Request\Core\UnguardedRequest;

class RegisterRequest extends UnguardedRequest
{

    public function getValidationProperties(): array
    {
        return [
            'login'    => [
                'required',
                'min:6',
                'max:255',
                'email'
            ],
            'name'     => 'required, min:6',
            'password' => 'required, min:6',
        ];
    }
}