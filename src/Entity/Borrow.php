<?php

namespace App\Entity;

use App\Repository\BorrowRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BorrowRepository::class)]
class Borrow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'borrows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'borrows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    #[ORM\Column]
    private ?\DateTime $borrowedAt = null;

    #[ORM\Column]
    private ?\DateTime $dueDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $returnedAt = null;

    #[ORM\Column(nullable: true)]
    private ?float $fine = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isReturned = false;

    public function __construct()
    {
        $this->borrowedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getBorrowedAt(): ?\DateTime
    {
        return $this->borrowedAt;
    }

    public function setBorrowedAt(\DateTime $borrowedAt): static
    {
        $this->borrowedAt = $borrowedAt;

        return $this;
    }

    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTime $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getReturnedAt(): ?\DateTime
    {
        return $this->returnedAt;
    }

    public function setReturnedAt(?\DateTime $returnedAt): static
    {
        $this->returnedAt = $returnedAt;

        return $this;
    }

    public function getFine(): ?float
    {
        $today = new \DateTime();

        // Case 1: Book not yet returned and overdue
        if ($this->dueDate && !$this->isReturned && $today > $this->dueDate) {
            $interval = $this->dueDate->diff($today);
            return $interval->days * 10.0; // ₹10/day fine
        }

        // Case 2: Book returned late
        if ($this->dueDate && $this->returnedAt && $this->returnedAt > $this->dueDate) {
            $interval = $this->dueDate->diff($this->returnedAt);
            return $interval->days * 10.0; // ₹10/day fine
        }

        // Case 3: Not late
        return 0.0;
    }


    public function isReturned(): bool
    {
        return $this->isReturned;
    }

    public function setIsReturned(bool $isReturned): self
    {
        $this->isReturned = $isReturned;
        return $this;
    }

}
