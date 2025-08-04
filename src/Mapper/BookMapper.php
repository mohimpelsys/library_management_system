<?php

namespace App\Mapper;

use App\Dto\BookDto;
use App\Entity\Book;

class BookMapper
{
    public static function toDto(Book $book): BookDto
    {
    return new BookDto(
    $book->getId(),
    $book->getTitle(),
    $book->getAuthor(),
    $book->getIsbn(),
    $book->getImage(),
    $book->getDescription()

    );
    }

    /** @param Book[] $books */
    public static function toDtoList(array $books): array
    {
        return array_map([self::class, 'toDto'], $books);
    }
}
