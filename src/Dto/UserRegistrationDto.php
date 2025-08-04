<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationDto
{
    #[Assert\NotBlank(message: 'Email should not be blank.')]
    #[Assert\Email(
        message: 'The email "{{ value }}" is not a valid email.',
        mode: 'strict'
    )]
    public string $email;

    #[Assert\NotBlank(message: 'Password should not be blank.')]
    #[Assert\Length(
        min: 8,
        minMessage: 'Password must be at least {{ limit }} characters long.'
    )]
    #[Assert\Regex(
        pattern: '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])/',
        message: 'Password must include at least 1 uppercase letter, 1 lowercase letter, 1 digit, and 1 special character.'
    )]
    public string $plainPassword;

    public function __construct(string $email = '', string $plainPassword = '')
    {
        $this->email = $email;
        $this->plainPassword = $plainPassword;
    }
}
