<?php

namespace App\Repository;

use App\Entity\Fournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Ldap\Security\LdapUser;
use App\Entity\User;

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
    public function searchbilanfournisseur(User $userof) : array
    {
           $query = $this
            ->createQueryBuilder('fournisseur');

        $query->innerJoin('App\Entity\Projet', 'projet', 'WITH', 'projet.fournisseur = fournisseur.id')
            ->innerJoin('App\Entity\Paiement', 'paiement', 'WITH', 'paiement.id = projet.paiement');

        $query = $query
            ->andWhere('paiement.id =:keya')
            ->setParameter('keya', 1);

        $query = $query
            ->andWhere('fournisseur.interlocuteur =:key')
            ->setParameter('key',$userof );

        return $query->getQuery()->getResult();


    }




}
