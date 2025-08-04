<?php

namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;


class BorrowDto
{
    public int $id;
    public string $title;
    public string $userEmail;

    #[Assert\NotBlank(message: "Borrow date is required")]
    public \DateTimeInterface $borrowedAt;

    #[Assert\NotBlank(message: "Due date is required")]
    public ?\DateTimeInterface $dueDate = null;
    public ?\DateTimeInterface $returnedAt = null;
    public ?float $fine = null;

    public function __construct(
        int $id,
        string $title,
        string $userEmail,
        \DateTimeInterface $borrowedAt,
        ?\DateTimeInterface $dueDate,
        ?\DateTimeInterface $returnedAt,
        ?float $fine
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->userEmail = $userEmail;
        $this->borrowedAt = $borrowedAt;
        $this->dueDate = $dueDate;
        $this->returnedAt = $returnedAt;
        $this->fine = $fine;
    }

    public function getBorrowedAt(): \DateTimeInterface
    {
        return $this->borrowedAt;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function getReturnedAt(): ?\DateTimeInterface
    {
        return $this->returnedAt;
    }

    public function getFine(): ?float
    {
        return $this->fine;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getId(): int
    {
        return $this->id;
    }

}
