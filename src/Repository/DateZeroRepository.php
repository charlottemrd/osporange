<?php

namespace App\Repository;

use App\Entity\DateZero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DateZero|null find($id, $lockMode = null, $lockVersion = null)
 * @method DateZero|null findOneBy(array $criteria, array $orderBy = null)
 * @method DateZero[]    findAll()
 * @method DateZero[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateZeroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateZero::class);
    }

    // /**
    //  * @return DateZero[] Returns an array of DateZero objects
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
    public function findOneBySomeField($value): ?DateZero
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
