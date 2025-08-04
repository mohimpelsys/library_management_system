<?php

namespace App\Mapper;

use App\Dto\BorrowDto;
use App\Entity\Borrow;
use App\Entity\Book;
use App\Entity\User;


class BorrowFormMapper
{
    public static function toDto(Borrow $borrow): BorrowDto
    {
        return new BorrowDto(
            id: $borrow->getId(),
            title: $borrow->getBook()->getTitle(),
            userEmail: $borrow->getUser()->getEmail(),
            borrowedAt: $borrow->getBorrowedAt(),
            dueDate: $borrow->getDueDate(),
            returnedAt: $borrow->getReturnedAt(),
            fine: $borrow->getFine()
        );
    }

    public static function toEntity(BorrowDto $dto, Book $book, User $user): Borrow
    {
        $borrow = new Borrow();
        $borrow->setBook($book);
        $borrow->setUser($user);

        $borrow->setBorrowedAt(new \DateTime($dto->borrowedAt->format('Y-m-d H:i:s')));

        if ($dto->dueDate) {
            $borrow->setDueDate(new \DateTime($dto->dueDate->format('Y-m-d H:i:s')));
        }

        if ($dto->returnedAt) {
            $borrow->setReturnedAt(new \DateTime($dto->returnedAt->format('Y-m-d H:i:s')));
        }

        return $borrow;
    }

}
