<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface; // <- add at top


abstract class BaseWebTestCase extends WebTestCase
{
    protected function loginTestUser($client): void
    {
        $user = static::getContainer()
            ->get('doctrine')
            ->getRepository(User::class)
            ->findOneByEmail('testuser@example.com');

        $client->loginUser($user);
    }

    protected function getJsonResponseContent(): array
    {
        return json_decode(static::getClient()->getResponse()->getContent(), true);
    }
    protected function getEntityManager(): EntityManagerInterface
    {
        return self::getContainer()->get('doctrine')->getManager();
    }
}
