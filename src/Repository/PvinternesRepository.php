<?php

namespace App\Repository;

use App\Entity\Datepvinterne;
use App\Entity\Pvinternes;
use App\Entity\Searchpv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pvinternes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pvinternes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pvinternes[]    findAll()
 * @method Pvinternes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PvinternesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pvinternes::class);
    }

    // /**
    //  * @return Pvinternes[] Returns an array of Pvinternes objects
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
    public function findOneBySomeField($value): ?Pvinternes
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    /**
     * Récupère les produits en lien avec une recherche
     * @return Pvinternes[]
     */
    public function findSearchpv(Searchpv $search,int $dateof) : array
    {

        $query = $this
            ->createQueryBuilder('pvinternes');
        $query->innerJoin('App\Entity\Datepvinterne', 'datepvinterne', 'WITH', 'datepvinterne.id = pvinternes.date')
        ;
        $query->innerJoin('App\Entity\Projet', 'projet', 'WITH', 'pvinternes.projet = projet.id')
        ;


        if (!empty($search->ref)) {
            $query = $query
                ->andWhere('projet.description LIKE =:ref')
                ->setParameter('ref', "%{$search->ref}%");
        }

        $query = $query
            ->andWhere('datepvinterne.id =:date')
            ->setParameter('date', $dateof);

        if (null !== $search->accept ) {
            if($search->accept==0) {
                $query = $query
                    ->andwhere('(pvinternes.isvalidate) =:az')
                    ->setParameter('az', false);
            }
            else{
                $query = $query
                    ->andwhere('(pvinternes.isvalidate) =:az')
                    ->setParameter('az', true);
            }
        }


        return $query->getQuery()->getResult();

    }

    /**
     * Récupère les produits en lien avec une recherche
     * @return Pvinternes[]
     */
    public function maxpv(int $pvid)
    {
        $query = $this
            ->createQueryBuilder('pvinternes');

        $query = $query
            ->andWhere('pvinternes.projet =:id')
            ->setParameter('id', $pvid);


        $query = $query
            ->andWhere('pvinternes.isvalidate =:bool')
            ->setParameter('bool', true);

        return $query->getQuery()->getResult();

    }




}
