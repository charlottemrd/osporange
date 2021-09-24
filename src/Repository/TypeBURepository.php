<?php

namespace App\Repository;

use App\Entity\TypeBU;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeBU|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeBU|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeBU[]    findAll()
 * @method TypeBU[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeBURepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeBU::class);
    }

    // /**
    //  * @return TypeBU[] Returns an array of TypeBU objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeBU
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
