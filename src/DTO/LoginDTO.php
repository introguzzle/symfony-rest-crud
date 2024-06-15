<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class LoginDTO
{
    #[Assert\NotBlank(message: 'Email should not be blank.')]
    #[Assert\NotNull(message: 'Email should not be null.')]
    #[Assert\Length(min: 3, minMessage: 'Name should be at least {{ limit }} characters.')]
    public ?string $login;

    #[Assert\NotBlank(message: 'Password should not be blank.')]
    #[Assert\NotNull(message: 'Password should not be null.')]
    #[Assert\Length(min: 6, minMessage: 'Password should be at least {{ limit }} characters.')]
    public ?string $password;

    public function __construct(?string $login, ?string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }
}