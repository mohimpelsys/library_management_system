<?php

namespace App\Controller\Api;

use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Mapper\BookApiMapper;

#[Route('/api/books')]
class BookApiController extends AbstractController
{
    #[Route('', name: 'api_books_list', methods: ['GET'])]
    public function getBooks(BookRepository $bookRepository, BookApiMapper $bookApiMapper): JsonResponse
    {
        $books = $bookRepository->findAll();
        $bookDtos = $bookApiMapper->toDtoList($books);

        return $this->json($bookDtos);
    }

    #[Route('/search', name: 'api_books_search', methods: ['GET'])]
    public function searchBooks(Request $request, BookRepository $bookRepository, BookApiMapper $bookApiMapper): JsonResponse
    {
        $keyword = $request->query->get('q', '');

        if (empty($keyword)) {
            return $this->json(['error' => 'Missing query parameter: q'], 400);
        }

        $books = $bookRepository->searchByTitleAuthorIsbn($keyword);
        $bookDtos = $bookApiMapper->toDtoList($books);

        return $this->json($bookDtos);
    }
    #[Route('/{id}', name: 'api_books_delete', methods: ['DELETE'])]
    public function deleteBook(int $id, BookRepository $bookRepository, EntityManagerInterface $em): JsonResponse
    {
        $book = $bookRepository->find($id);

        if (!$book || $book->isDeleted()) {
            return $this->json(['error' => 'Book not found or already deleted'], 404);
        }

        $book->setIsDeleted(true);
        $em->flush();

        return $this->json(['message' => 'Book deleted successfully']);
    }

}
