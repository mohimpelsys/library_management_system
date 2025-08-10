<?php

namespace App\Tests\Controller\Api;

use App\Entity\Book;
use App\Tests\BaseWebTestCase;


class BookApiControllerTest extends BaseWebTestCase
{
    public function testGetBooksAsAuthenticatedUser(): void
    {
        $client = static::createClient();
        $this->loginTestUser($client);

        $client->request('GET', '/api/books');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testGetBookByTitle(): void
    {
        $client = static::createClient();
        $this->loginTestUser($client);

        $client->request('GET', '/api/books?title=Harry Potter');

        $this->assertResponseIsSuccessful();
    }

    public function testGetBookByAuthor(): void
    {
        $client = static::createClient();
        $this->loginTestUser($client);

        $client->request('GET', '/api/books?author=Rowling');

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteBook(): void
    {
        $client = static::createClient();
        $this->loginTestUser($client);

        $entityManager = $this->getEntityManager();

        $book = new Book();
        $book->setTitle('Temp Book for Deletion');
        $book->setAuthor('Test Author');
        $book->setIsbn('999999999');
        $book->setDescription('Temporary book just for deletion test');

        $entityManager->persist($book);
        $entityManager->flush();

        $client->request('DELETE', '/api/books/' . $book->getId());

        $this->assertResponseStatusCodeSame(200);
    }
}
