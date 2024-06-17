<?php

namespace App\Request\Security;

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

    public function prepare(): void
    {
        // TODO: Implement prepare() method.
    }
}