<?php

namespace App\Controller;

use App\Dto\BookFormDto;
use App\Entity\Book;
use App\Form\BookType;
use App\Mapper\BookFormMapper;
use App\Mapper\BookMapper;
use App\Repository\BookRepository;
//use App\Dto\BookDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
//use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/book')]
final class BookController extends AbstractController
{
    private BookFormMapper $bookFormMapper;
    public function __construct(BookFormMapper $bookFormMapper)
    {
        $this->bookFormMapper = $bookFormMapper;
    }
    #[Route(name: 'app_book_index', methods: ['GET'])]
    public function index(Request $request, BookRepository $bookRepository): Response
    {
        $user = $this->getUser();
        $borrowedBookIds = [];

        if ($user) {
            foreach ($user->getBorrows() as $borrow) {
                if ($borrow->getReturnedAt() === null) {
                    $borrowedBookIds[] = $borrow->getBook()->getId();
                }
            }
        }


        $searchTerm = $request->query->get('q', '');
        $sort = $request->query->get('sort', ''); // Get the sorting field

        $qb = $bookRepository->createQueryBuilder('b')
            ->where('b.isDeleted = false');

        if ($searchTerm) {
            $qb->andWhere('b.title LIKE :term OR b.author LIKE :term OR b.isbn LIKE :term')
                ->setParameter('term', '%' . $searchTerm . '%');
        }

        // Apply sorting if requested
        if ($sort === 'title') {
            $qb->orderBy('b.title', 'ASC');
        } elseif ($sort === 'isbn') {
            $qb->orderBy('b.isbn', 'ASC');
        }

        $books = $qb->getQuery()->getResult();
        $bookDtos = BookMapper::toDtoList($books);

        return $this->render('book/index.html.twig', [
            'books' => $bookDtos,
            'borrowedBookIds' => $borrowedBookIds,
            'searchTerm' => $searchTerm,
        ]);
    }


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        ValidatorInterface $validator

    ): Response {
        $dto = new BookFormDto();
        $form = $this->createForm(BookType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $errors = $validator->validate($dto);

            if (count($errors) === 0 && $form->isValid()) {
                $book = BookFormMapper::toEntity($dto);

                $this->bookFormMapper->updateEntityFromDto($dto, $book, $slugger);

                $entityManager->persist($book);
                $entityManager->flush();

                $this->addFlash('success', '✅ Book added successfully!');
                return $this->redirectToRoute('app_book_index');
            }
            $this->addFlash('danger', '❌ Book creation failed. Please fix the errors and try again.');
        }
        return $this->render('book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        $bookDto = BookMapper::toDto($book);
        return $this->render('book/show.html.twig', [
            'book' => $bookDto,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Book $book,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        BookFormMapper $bookFormMapper
    ): Response {
        // STEP 1: Convert Book entity to DTO
        $bookFormDto = $bookFormMapper->entityToDto($book);

        // STEP 2: Create and handle the form
        $form = $this->createForm(BookType::class, $bookFormDto, [
            'is_edit' => true, // disables ISBN field
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // STEP 3: Update the existing book with new data
            $bookFormMapper->updateEntityFromDto($bookFormDto, $book, $slugger);

            // STEP 4: Save changes
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Book $book, EntityManagerInterface $em): Response
    {
        $book->setIsDeleted(true);
        $em->flush();

        $this->addFlash('success', 'Book deleted successfully (soft delete).');
        return $this->redirectToRoute('app_book_index');
    }
}
