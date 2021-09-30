<?php

namespace App\Repository;

use App\Entity\DateTwo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DateTwo|null find($id, $lockMode = null, $lockVersion = null)
 * @method DateTwo|null findOneBy(array $criteria, array $orderBy = null)
 * @method DateTwo[]    findAll()
 * @method DateTwo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateTwoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateTwo::class);
    }

    // /**
    //  * @return DateTwo[] Returns an array of DateTwo objects
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
    public function findOneBySomeField($value): ?DateTwo
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
