<?php

namespace App\Repository;

use App\Entity\Projet;
use App\Entity\SearchData;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use LdapTools\Bundle\LdapToolsBundle\Security\User\LdapUser;

/**
 * @method Projet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projet[]    findAll()
 * @method Projet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projet::class);
    }


    /**
     * Récupère les produits en lien avec une recherche
     * @return Projet[]
     */
    public function findSearch(SearchData $search, User $user) : array
    {


            $query = $this
            ->createQueryBuilder('o');
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {


            $query = $query
            ->andWhere('o.userchef =:user')
            ->setParameter('user',$user );
            }
       if (!empty($search->q)) {
           $query = $query
                ->andWhere('o.description LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->ref)) {
            $query = $query
                ->andWhere('o.reference LIKE :ref')
                ->setParameter('ref', "%{$search->ref}%");
        }

        if (!empty($search->fournisseurs)) {
            $query = $query->andWhere('o.fournisseur IN (:fournisseurs)')
                ->setParameter('fournisseurs', $search->fournisseurs);
        }



        if (!empty($search->domain)) {
            $query = $query
                ->andWhere('o.domaine LIKE :domain')
                ->setParameter('domain', "%{$search->domain}%");
        }

        if (!empty($search->sdomain)) {
            $query = $query
                ->andWhere('o.sdomaine LIKE :sdomain')
                ->setParameter('sdomain', "%{$search->sdomain}%");
        }


        if (!empty($search->phases)) {
            $query = $query
                     ->andWhere('o.Phase IN (:phases)')
                ->setParameter('phases', $search->phases);
        }

        if (!empty($search->risques)) {
            $query = $query->andWhere('o.risque IN (:risques)')
                ->setParameter('risques', $search->risques);
        }


        if (!empty($search->bu)) {
            $query = $query
                ->andWhere('o.typebu IN (:bu)')
                ->setParameter('bu', $search->bu);
        }

        if (!empty($search->priority)) {
            $query = $query->andWhere('o.priorite IN (:priority)')
                ->setParameter('priority', $search->priority);
        }

        return $query->getQuery()->getResult();

    }

    /**
     * Récupère les produits en lien avec une recherche
     * @return Projet[]
     */
    public function findSearchMof(SearchData $search, User $user) : array
    {

        $query = $this
            ->createQueryBuilder('o');
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {



            $query = $query
                ->andWhere('o.userchef =:user')
                ->setParameter('user', $user);
        }
        if (!empty($search->q)) {
            $query = $query
                ->andWhere('o.description LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->ref)) {
            $query = $query
                ->andWhere('o.reference LIKE :ref')
                ->setParameter('ref', "%{$search->ref}%");
        }

        if (!empty($search->fournisseurs)) {
            $query = $query->andWhere('o.fournisseur IN (:fournisseurs)')
                ->setParameter('fournisseurs', $search->fournisseurs);
        }



        if (!empty($search->domain)) {
            $query = $query
                ->andWhere('o.domaine LIKE :domain')
                ->setParameter('domain', "%{$search->domain}%");
        }

        if (!empty($search->sdomain)) {
            $query = $query
                ->andWhere('o.sdomaine LIKE :sdomain')
                ->setParameter('sdomain', "%{$search->sdomain}%");
        }


        if (!empty($search->phases)) {
            $query = $query
                ->andWhere('o.Phase IN (:phases)')
                ->setParameter('phases', $search->phases);
        }

        if (!empty($search->risques)) {
            $query = $query->andWhere('o.risque IN (:risques)')
                ->setParameter('risques', $search->risques);
        }


        if (!empty($search->bu)) {
            $query = $query
                ->andWhere('o.typebu IN (:bu)')
                ->setParameter('bu', $search->bu);
        }

        if (!empty($search->priority)) {
            $query = $query->andWhere('o.priorite IN (:priority)')
                ->setParameter('priority', $search->priority);
        }
        $query = $query->andWhere('o.paiement IN (:paye)')
            ->setParameter('paye', 2);

        return $query->getQuery()->getResult();

    }



    /**
     * Récupère les produits en lien avec une recherche
     * @return Projet[]
     */
    public function findSearchProjetM(  User $user) : array
    {

        $query = $this
            ->createQueryBuilder('o');
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {


            $query = $query
                ->andWhere('o.user =:userchef')
                ->setParameter('user', $user);
        }



            $query = $query->andWhere('o.paiement IN (:paye)')
                ->setParameter('paye', 2);




        return $query->getQuery()->getResult();

    }



    /**
     * Récupère les produits en lien avec une recherche
     * @return Projet[]
     */
    public function findexportSearch(User $user) : array
    {

        $query = $this
            ->createQueryBuilder('o');
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {


            $query = $query
                ->andWhere('o.userchef =:user')
                ->setParameter('user', $user);
        }






        return $query->getQuery()->getResult();

    }


    /**
     * Récupère les produits en lien avec une recherche
     * @return Projet|null
     */
    public function findprojetbyname($name)
    {

        $query = $this
            ->createQueryBuilder('ol');
            $query = $query
                ->andWhere('ol.reference LIKE :ref')
                ->setParameter('ref', $name);

        return $query->getQuery()->getResult();

    }


    /**
     * Récupère les produits en lien avec une recherche
     * @return Projet[]
     */
    public function createRef(User $user) : array
    {
        $localdate=new \DateTime();
        $from = new \DateTime($localdate->format("Y-m-d")." 00:00:00");
        $to   = new \DateTime($localdate->format("Y-m-d")." 23:59:59");

        $query = $this
            ->createQueryBuilder('o');


           $query = $query
                ->andWhere('o.userchef =:user')
                ->setParameter('user', $user);

        $query = $query
            ->andWhere('o.datecrea BETWEEN :from AND :to')
            ->setParameter('from', $from )
            ->setParameter('to', $to);
        return $query->getQuery()->getResult();

    }







    // /**
    //  * @return Projet[] Returns an array of Projet objects
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
    public function findOneBySomeField($value): ?Projet
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
