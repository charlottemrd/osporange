<?php

namespace App\Repository;

use App\Entity\Modalites;
use App\Entity\Projet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Modalites|null find($id, $lockMode = null, $lockVersion = null)
 * @method Modalites|null findOneBy(array $criteria, array $orderBy = null)
 * @method Modalites[]    findAll()
 * @method Modalites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModalitesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modalites::class);
    }

    /**
     * Récupère les produits en lien avec une recherche
     * @return Modalites[]
     */
    public function isreadytobeapproved(int $avantpourcentage,Projet $projet) : array
    {

        $query = $this
            ->createQueryBuilder('o');

            $query = $query
                ->andWhere('o.pourcentage < :to')
                ->setParameter('to', $avantpourcentage);



            $query = $query
                ->andWhere('o.isapproved =:user')
                ->setParameter('user', false);
        $query = $query
            ->andWhere('o.projet =:po')
            ->setParameter('po', $projet);








        return $query->getQuery()->getResult();

    }




    // /**
    //  * @return Modalites[] Returns an array of Modalites objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Modalites
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
