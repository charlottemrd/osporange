<?php

namespace App\Repository;

use App\Entity\Datepvinterne;
use App\Entity\SearchDatePv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Datepvinterne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Datepvinterne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Datepvinterne[]    findAll()
 * @method Datepvinterne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatepvinterneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Datepvinterne::class);
    }

    // /**
    //  * @return Datepvinterne[] Returns an array of Datepvinterne objects
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
    public function findOneBySomeField($value): ?Datepvinterne
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Récupère les produits en lien avec une recherche
     * @return Datepvinterne
     */
    public function owndatepv(int $month,int$year)
    {

        $query = $this
            ->createQueryBuilder('datepvinterne');
        $query = $query
            ->andwhere('MONTH(datepvinterne.datemy) = :monti')
            ->setParameter('monti', $month);
        $query = $query
            ->andwhere('YEAR(datepvinterne.datemy) =:yeari')
            ->setParameter('yeari', $year);
        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * Récupère les produits en lien avec une recherche
     * @return Idmonthbm[]
     */
    public function searchbilanmensuelfournisseur(SearchDatePv $search) : array
    {

        $query = $this
            ->createQueryBuilder('datepv');


        if (!empty($search->month)) {
            $query = $query
                ->andwhere('MONTH(datepv.datemy) = :monti')
                ->setParameter('monti', $search->month);
        }

        if (!empty($search->year)) {

            $query = $query
                ->andwhere('YEAR(datepv.datemy) LIKE :yeari')
                ->setParameter('yeari',"%{$search->year}%");
        }
        $query=$query
            ->orderBy('datepv.id','DESC');





        return $query->getQuery()->getResult();


    }



}
