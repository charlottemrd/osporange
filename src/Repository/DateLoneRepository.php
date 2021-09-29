<?php

namespace App\Repository;

use App\Entity\DateLone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DateLone|null find($id, $lockMode = null, $lockVersion = null)
 * @method DateLone|null findOneBy(array $criteria, array $orderBy = null)
 * @method DateLone[]    findAll()
 * @method DateLone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateLoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateLone::class);
    }

    // /**
    //  * @return DateLone[] Returns an array of DateLone objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DateLone
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
