<?php
// src/Mapper/BookApiMapper.php
namespace App\Mapper;

use App\Dto\BookDto;
use App\Entity\Book;

class BookApiMapper
{
    public function toDto(Book $book): BookDto
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

    /** @return BookDto[] */
    public function toDtoList(array $books): array
    {
        return array_map([$this, 'toDto'], $books);
    }
}
