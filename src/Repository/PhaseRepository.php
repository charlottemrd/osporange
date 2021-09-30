<?php

namespace App\Repository;

use App\Entity\Phase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Phase|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phase|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phase[]    findAll()
 * @method Phase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phase::class);
    }



    /**
     * Récupère les produits en lien avec une recherche
     * @return Phase[]
     */
    public function reqbPhase( int $avant, int $cad,int $susp) : array
    {

        $query = $this
            ->createQueryBuilder('o');


        $query = $query
            ->andWhere('o.ordere =:av  OR o.ordere =:ca OR o.ordere =:sus')
            ->setParameter('av', $avant)
            ->setParameter('ca', $cad)
            ->setParameter('sus', $susp)
            ->orderBy('o.ordere', 'ASC');

        return $query->getQuery()->getResult();

    }

    /**
     * Récupère les produits en lien avec une recherche
     * @return Phase[]
     */
    public function reqaPhase( int $avant, int $dema,int $cad,int $susp) : array
    {

        $query = $this
            ->createQueryBuilder('o');


        $query = $query
            ->andWhere('o.ordere =:av  OR o.ordere =:ca OR o.ordere =:dem OR o.ordere =:sus')
            ->setParameter('av', $avant)
            ->setParameter('ca', $cad)
            ->setParameter('dem', $dema)
            ->setParameter('sus', $susp)
            ->orderBy('o.ordere', 'ASC');

        return $query->getQuery()->getResult();

    }




    // /**
    //  * @return Phase[] Returns an array of Phase objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Phase
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
