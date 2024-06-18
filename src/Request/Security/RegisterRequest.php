<?php

namespace App\Request\Security;

use App\Other\ValidationProperties;
use App\Request\Core\UnguardedRequest;

class RegisterRequest extends UnguardedRequest
{

    public function getValidationProperties(): ValidationProperties
    {
        return new ValidationProperties([
            'login'    => [
                'required',
                'unique:users/email',
                'min:6',
                'max:255',
                'email',
            ],
            'name'                  => 'required, min:6, unique:users/email, max',
            'password'              => 'required, min:6, max',
            'password_confirmation' => 'required, min:6, same:password'
        ]);
    }

    public function getMessages(): array
    {
        return [
            'login.unique' => ':property already exists',
            'password_confirmation.same' => 'Password does not match!!!!',
            'login.custom' => 'ZZZZZZZZZZZZZZZZZZZZZ',
        ];
    }

    public function messageProperties(): array
    {
        return [
            'login' => 'Логин'
        ];
    }
}