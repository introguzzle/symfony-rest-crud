<?php

namespace App\Request\Security;

use App\Other\Constraint\Assert\RequestConstraint;
use App\Request\Core\UnguardedRequest;

class RegisterRequest extends UnguardedRequest
{

    public function getValidationProperties(): array
    {
        return [
            'login'    => [
                'required',
                'unique:users/email',
                'min:6',
                'max:255',
                'email',
                new RequestConstraint('custom', function (mixed $login) {
                    return false;
                }),

            ],
            'name'     => 'required, min:6, unique:users/email, max',
            'password' => 'required, min:6, max',
            'password_confirmation' => 'required, min:6, same:password'
        ];
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

    public function prepare(): void
    {
        // TODO: Implement prepare() method.
    }
}