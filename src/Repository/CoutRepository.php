<?php

namespace App\Repository;

use App\Entity\Cout;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cout|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cout|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cout[]    findAll()
 * @method Cout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cout::class);
    }

    // /**
    //  * @return Cout[] Returns an array of Cout objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cout
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
