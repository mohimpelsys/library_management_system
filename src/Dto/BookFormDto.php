<?php
namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BookFormDto
{
    #[Assert\NotBlank(message: "Title is required.")]
    #[Assert\Length(min: 4, max: 255, minMessage: "Title must be at least {{ limit }} characters.")]
    public string $title = '';

    #[Assert\NotBlank(message: "Author is required.")]
    #[Assert\Length(min: 4, minMessage: "Author must be at least 4 characters.")]
    public string $author = '';

    #[Assert\NotBlank(message: "ISBN is required.")]
    #[Assert\Length(min: 3, minMessage:"ISBN must be at least 3 characters." )]
    public string $isbn = '';

    #[Assert\File(
        maxSize: '10M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
        maxSizeMessage: 'Image size must be under 10MB.',
        mimeTypesMessage: 'Please upload a valid image (JPEG, PNG, or WebP).'
    )]
    public ?UploadedFile $image = null;

    #[Assert\Length(
        max: 1000,
        maxMessage: 'Description must not exceed 1000 characters.'
    )]
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
