<?php

namespace App\Controller;

use App\Dto\BorrowDto;
use App\Entity\Borrow;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/user')]
//#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
//    #[Route('/borrows', name: 'user_borrows_page')]
//    public function borrowedBooks(EntityManagerInterface $em): Response
//    {
//        $user = $this->getUser();
//        $borrows = $em->getRepository(Borrow::class)->findBy(['user' => $user]);
//
//        $today = new \DateTime();
//        $borrowDtos = [];
//
//        foreach ($borrows as $borrow) {
//            if ($borrow->getReturnedAt() === null) {
//                $dueDate = $borrow->getDueDate();
//                $daysLate = $today > $dueDate ? $today->diff($dueDate)->days : 0;
//                $fine = $daysLate * 10;
//
//                $borrowDtos[] = new BorrowDto(
//                    $borrow->getId(),
//                    $borrow->getBook()->getTitle(),
//                    $borrow->getUser()->getEmail(),
//                    $borrow->getBorrowedAt(),
//                    $borrow->getDueDate(),
//                    $borrow->getReturnedAt(),
//                    $fine
//                );
//            }
//        }
//
//        return $this->render('borrow/borrowed_books.html.twig', [
//            'borrows' => $borrowDtos,
//        ]);
//    }
}
