<?php
namespace App\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class BookDto
{
    public int $id;

    #[Assert\NotBlank(message: 'Title is required.')]
    #[Assert\Length(
        min: 4,
        max: 255,
        minMessage: 'Title must be at least {{ limit }} characters.',
        maxMessage: 'Title cannot be longer than {{ limit }} characters.'
    )]
    public string $title;

    #[Assert\NotBlank(message: 'Author is required.')]
    #[Assert\Length(
        min: 4,
        max: 255,
        minMessage: 'Author name must be at least {{ limit }} characters.'
    )]
    public string $author;

    #[Assert\NotBlank(message: 'ISBN is required.')]
    #[Assert\Length(
        min: 3,
        max: 13,
        exactMessage: 'ISBN must be at least {{ limit }} characters.'
    )]
    public string $isbn;


    #[Assert\File(
        maxSize: '10M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
        maxSizeMessage: 'Image size must be under 10MB.',
        mimeTypesMessage: 'Please upload a valid image (JPEG, PNG, or WebP).'
    )]
    public ?UploadedFile $image = null;

    #[Assert\Length(
        max: 500,
        maxMessage: 'Description must not exceed {{ limit }} characters.'
    )]
    public ?string $description = null;

    public function __construct(
        int $id,
        string $title,
        string $author,
        string $isbn,
        ?string $image,
        ?string $description
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->image = $image;
        $this->description = $description;
    }
}
