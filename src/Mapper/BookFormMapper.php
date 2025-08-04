<?php

namespace App\Mapper;

use App\Dto\BookFormDto;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class BookFormMapper
{
    public function __construct(private string $projectDir) {}

    public static function fromEntity(Book $book): BookFormDto
    {
        $dto = new BookFormDto();
        $dto->title = $book->getTitle();
        $dto->author = $book->getAuthor();
        $dto->isbn = $book->getIsbn();
        $dto->setDescription($book->getDescription()); // ✅ fixed
        return $dto;
    }

    public static function toEntity(BookFormDto $dto, ?Book $book = null): Book
    {
        $book ??= new Book();
        $book->setTitle($dto->title);
        $book->setAuthor($dto->author);
        $book->setIsbn($dto->isbn);
        $book->setDescription($dto->getDescription()); // ✅ fixed
        // image handled elsewhere
        return $book;
    }

    public function entityToDto(Book $book): BookFormDto
    {
        $dto = new BookFormDto();
        $dto->title = $book->getTitle();
        $dto->author = $book->getAuthor();
        $dto->isbn = $book->getIsbn();
        $dto->setDescription($book->getDescription()); // ✅ correct
        return $dto;
    }

    public function updateEntityFromDto(BookFormDto $dto, Book $book, SluggerInterface $slugger): void
    {
        $book->setTitle($dto->title);
        $book->setAuthor($dto->author);
        $book->setIsbn($dto->isbn);
        $book->setDescription($dto->getDescription()); // ✅ added

        $imageFile = $dto->image;
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->projectDir . '/public/uploads',
                    $newFilename
                );
                $book->setImage($newFilename);
            } catch (FileException $e) {
                // Optionally log
            }
        }
    }
}
