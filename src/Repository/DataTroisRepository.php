<?php

namespace App\Repository;

use App\Entity\DataTrois;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataTrois|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataTrois|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataTrois[]    findAll()
 * @method DataTrois[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataTroisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataTrois::class);
    }

    // /**
    //  * @return DataTrois[] Returns an array of DataTrois objects
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
    public function findOneBySomeField($value): ?DataTrois
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
