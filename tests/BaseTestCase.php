<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

abstract class BaseTestCase extends KernelTestCase
{
    protected ValidatorInterface $validator;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->validator = static::getContainer()->get(ValidatorInterface::class);
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
    }

    protected function validateDto(object $dto)
    {
        return $this->validator->validate($dto);
    }

    protected function getTestUser(): ?User
    {
        return static::getContainer()
            ->get('doctrine')
            ->getRepository(User::class)
            ->findOneByEmail('testuser@example.com');
    }
}
