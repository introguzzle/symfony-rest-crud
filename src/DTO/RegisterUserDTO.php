<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserDTO
{
    #[Assert\NotBlank(message: 'Name should not be blank.')]
    #[Assert\Length(min: 3, minMessage: 'Name should be at least {{ limit }} characters.')]
    public string $name;

    #[Assert\NotBlank(message: 'Password should not be blank.')]
    #[Assert\Length(min: 6, minMessage: 'Password should be at least {{ limit }} characters.')]
    public string $password;

    #[Assert\NotBlank(message: 'Email should not be blank.')]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email.')]
    public string $email;

    public function __construct(string $name, string $password, string $email)
    {
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
    }
}