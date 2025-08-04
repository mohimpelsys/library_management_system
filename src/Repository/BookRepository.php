<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    // src/Repository/BookRepository.php

    public function findAll(): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.isDeleted = false')
            ->getQuery()
            ->getResult();
    }
    public function searchByTitleAuthorIsbn(string $keyword): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.isDeleted = false')
            ->where('LOWER(b.title) LIKE :kw')
            ->orWhere('LOWER(b.author) LIKE :kw')
            ->orWhere('LOWER(b.isbn) LIKE :kw')
            ->setParameter('kw', '%' . strtolower($keyword) . '%')
            ->getQuery()
            ->getResult();
    }


}
