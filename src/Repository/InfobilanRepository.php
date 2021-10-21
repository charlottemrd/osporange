<?php

namespace App\Repository;

use App\Entity\Infobilan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Infobilan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Infobilan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Infobilan[]    findAll()
 * @method Infobilan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfobilanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Infobilan::class);
    }

    // /**
    //  * @return Infobilan[] Returns an array of Infobilan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Infobilan
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
