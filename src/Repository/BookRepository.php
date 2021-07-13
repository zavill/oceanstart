<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @param $criteria
     * @param null $orderBy
     * @param null $limit
     * @param null $offset
     * @return Book[]
     */
    public function findBy($criteria, $orderBy = null, $limit = null, $offset = null): array
    {
        $query = $this->createQueryBuilder('b');
        foreach ($criteria as $propName => $propValue) {
            if ($propName === 'coAuthor') {
                if (empty($propValue[0])) {
                    continue;
                }
                if (is_array($propValue)) {
                    foreach ($propValue as $key=>$value) {
                        $query->andWhere(":coAuthor$key MEMBER of b.coAuthor");
                        $query->setParameter("coAuthor$key", $value);
                    }
                } else {
                    $query->andWhere(':coAuthor MEMBER of b.coAuthor');
                    $query->setParameter('coAuthor', $propValue);
                }
            } else {
                $query->andWhere("b.$propName = :$propName");
                $query->setParameter("$propName", $propValue);
            }
        }

        if ($orderBy !== null) {
            foreach ($orderBy as $sortName => $sortType) {
                $query->addOrderBy("b.$sortName", $sortType);
            }
        }

        if ($limit !== null) {
            $query->setMaxResults($limit);
        }

        if ($offset !== null) {
            $query->setFirstResult($offset);
        }

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
