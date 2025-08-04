<?php

namespace App\Dto;

class UserDto
{
    public function __construct(
        public int $id,
        public string $email,
        public array $roles
    ) {}
}
