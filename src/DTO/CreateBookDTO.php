<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
class CreateBookDTO
{
    #[Assert\NotBlank(message: 'Title should not be blank.')]
    #[Assert\NotNull(message: 'Title should not be null.')]
    public ?string $title;

    #[Assert\NotBlank(message: 'Author should not be blank.')]
    #[Assert\NotNull(message: 'Author should not be null.')]
    public ?string $author;

    /**
     * @param string|null $title
     * @param string|null $author
     */
    public function __construct(?string $title, ?string $author)
    {
        $this->title = $title;
        $this->author = $author;
    }
}