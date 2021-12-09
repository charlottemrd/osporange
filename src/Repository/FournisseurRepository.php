<?php

namespace App\Repository;

use App\Entity\Fournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Ldap\Security\LdapUser;

/**
 * @method Fournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fournisseur[]    findAll()
 * @method Fournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fournisseur::class);
    }

    // /**
    //  * @return Fournisseur[] Returns an array of Fournisseur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fournisseur
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Récupère les produits en lien avec une recherche
     * @return Fournisseur[]
     */
    public function searchbilanfournisseur(String $userid) : array
    {
           $query = $this
            ->createQueryBuilder('fournisseur');

        $query->innerJoin('App\Entity\Projet', 'projet', 'WITH', 'projet.fournisseur = fournisseur.id')
            ->innerJoin('App\Entity\Paiement', 'paiement', 'WITH', 'paiement.id = projet.paiement');
        $query = $query
            ->andWhere('paiement.id =:key')
            ->setParameter('key', 1);

        $query = $query
            ->andWhere('fournisseur.fournisseurid =:key')
            ->setParameter('key',$userid );

        return $query->getQuery()->getResult();


    }




}
