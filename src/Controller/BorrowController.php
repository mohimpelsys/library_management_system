<?php

namespace App\Controller;

use App\Dto\BorrowDto;
use App\Entity\Borrow;
use App\Entity\Book;
use App\Form\BorrowType;
use App\Mapper\BorrowFormMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/borrow')]
class BorrowController extends AbstractController
{
    #[Route('/new', name: 'app_borrow_book')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        Security $security,
        BorrowFormMapper $borrowMapper
    ): Response {
        $bookId = $request->query->get('bookId');
        $book = $em->getRepository(Book::class)->find($bookId);

        if (!$book) {
            throw $this->createNotFoundException('Book not found.');
        }

        $user = $security->getUser();

        $borrowDto = new BorrowDto(
            id: 0,
            title: $book->getTitle(),
            userEmail: $user->getUserIdentifier(),
            borrowedAt: new \DateTime(),
            dueDate: (new \DateTime())->modify('+7 days'),
            returnedAt: null,
            fine: null
        );

        $form = $this->createForm(BorrowType::class, $borrowDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrow = $borrowMapper::toEntity($borrowDto, $book, $user);

            $em->persist($borrow);
            $em->flush();

            $this->addFlash('success', 'Book borrowed successfully!');
            return $this->redirectToRoute('user_borrows');
        }

        return $this->render('borrow/new.html.twig', [
            'form' => $form,
            'borrowDto' => $borrowDto,
        ]);
    }

    #[Route('/my-borrows', name: 'user_borrows')]
    public function myBorrows(EntityManagerInterface $em, Security $security): Response
    {
        $user = $security->getUser();

        $activeBorrows = $em->getRepository(Borrow::class)->findBy([
            'user' => $user,
            'isReturned' => false,
        ]);

        $returnedBorrows = $em->getRepository(Borrow::class)->findBy([
            'user' => $user,
            'isReturned' => true,
        ]);

        $activeDtos = array_map(fn(Borrow $b) => BorrowFormMapper::toDto($b), $activeBorrows);
        $returnedDtos = array_map(fn(Borrow $b) => BorrowFormMapper::toDto($b), $returnedBorrows);

        return $this->render('borrow/my_books.html.twig', [
            'currentBorrows' => $activeDtos,
            'returnedBorrows' => $returnedDtos,
        ]);
    }

    #[Route('/borrow/return/{id}', name: 'return_book', methods: ['POST'])]
    public function returnBook(Request $request, Borrow $borrow, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('return' . $borrow->getId(), $request->request->get('_token'))) {
            $borrow->setReturnedAt(new \DateTime());
            $borrow->setIsReturned(true);

            $entityManager->flush();

            $this->addFlash('success', 'Book returned successfully.');
        }

        return $this->redirectToRoute('user_borrows');
    }
}
