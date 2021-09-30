<?php

namespace App\Repository;

use App\Entity\DateOnePlus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DateOnePlus|null find($id, $lockMode = null, $lockVersion = null)
 * @method DateOnePlus|null findOneBy(array $criteria, array $orderBy = null)
 * @method DateOnePlus[]    findAll()
 * @method DateOnePlus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateOnePlusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateOnePlus::class);
    }

    // /**
    //  * @return DateOnePlus[] Returns an array of DateOnePlus objects
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
    public function findOneBySomeField($value): ?DateOnePlus
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
