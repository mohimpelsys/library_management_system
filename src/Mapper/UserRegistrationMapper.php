<?php

namespace App\Mapper;

use App\Dto\UserRegistrationDto;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationMapper
{
    public static function toEntity(UserRegistrationDto $dto, UserPasswordHasherInterface $hasher): User
    {
        $user = new User();
        $user->setEmail($dto->email);
        $user->setPassword($hasher->hashPassword($user, $dto->plainPassword)
        );

        return $user;
    }
}
