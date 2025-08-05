<?php
namespace App\Dto;


class BookDto
{
    public int $id;
    public string $title;
    public string $author;
    public string $isbn;
    public ?string $image = null;
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
