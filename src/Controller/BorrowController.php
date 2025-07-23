<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Entity\Book;
use App\Form\BorrowType;
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
    public function new(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $bookId = $request->query->get('bookId');
        $book = $em->getRepository(Book::class)->find($bookId);

        if (!$book) {
            throw $this->createNotFoundException('Book not found.');
        }

        $borrow = new Borrow();
        $borrow->setBook($book);
        $borrow->setUser($security->getUser()); // auto-assign current user

        $form = $this->createForm(BorrowType::class, $borrow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($borrow);
            $em->flush();

            return $this->redirectToRoute('user_borrows'); // or any other page
        }

        return $this->render('borrow/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/borrow/return/{id}', name: 'return_book', methods: ['POST'])]
    public function returnBook(Request $request, Borrow $borrow, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('return' . $borrow->getId(), $request->request->get('_token'))) {
            $entityManager->remove($borrow);
            $entityManager->flush();

            $this->addFlash('success', 'Book returned successfully.');
        }

        return $this->redirectToRoute('user_borrows');
    }
}
