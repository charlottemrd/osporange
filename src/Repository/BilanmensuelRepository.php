<?php

namespace App\Repository;

use App\Entity\Bilanmensuel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bilanmensuel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bilanmensuel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bilanmensuel[]    findAll()
 * @method Bilanmensuel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BilanmensuelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bilanmensuel::class);
    }

    // /**
    //  * @return Bilanmensuel[] Returns an array of Bilanmensuel objects
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
    public function findOneBySomeField($value): ?Bilanmensuel
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
