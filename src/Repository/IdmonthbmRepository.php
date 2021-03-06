<?php

namespace App\Repository;

use App\Entity\Idmonthbm;
use App\Entity\Projet;
use App\Entity\Bilanmensuel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Fournisseur;
use App\Entity\SearchBilanmensuel;

/**
 * @method Idmonthbm|null find($id, $lockMode = null, $lockVersion = null)
 * @method Idmonthbm|null findOneBy(array $criteria, array $orderBy = null)
 * @method Idmonthbm[]    findAll()
 * @method Idmonthbm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdmonthbmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Idmonthbm::class);
    }

    // /**
    //  * @return Idmonthbm[] Returns an array of Idmonthbm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Idmonthbm
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Récupère les produits en lien avec une recherche
     * @return Idmonthbm[]
     */
    public function searchbilanmensuelfournisseur(SearchBilanmensuel $search,Fournisseur $fournisseur) : array
    {

        $query = $this
            ->createQueryBuilder('bilanmensuel');

        $query->innerJoin('App\Entity\Fournisseur', 'fournisseur', 'WITH', 'bilanmensuel.fournisseur = fournisseur.id')
        ;
        $query = $query
            ->andWhere('bilanmensuel.fournisseur =:fou')
            ->setParameter('fou', $fournisseur);

        if (!empty($search->month)) {
            $query = $query
                ->andwhere('MONTH(bilanmensuel.monthyear) = :monti')
                ->setParameter('monti', $search->month);
        }

        if (!empty($search->year)) {

            $query = $query
                ->andwhere('YEAR(bilanmensuel.monthyear) LIKE :yeari')
                ->setParameter('yeari',"%{$search->year}%");
        }

        if (null !== $search->accept ) {
            if($search->accept==0) {
                $query = $query
                    ->andwhere('(bilanmensuel.isaccept) =:az')
                    ->setParameter('az', false);
            }
            else{
                $query = $query
                    ->andwhere('(bilanmensuel.isaccept) =:az')
                    ->setParameter('az', true);
            }
        }
        $query=$query
            ->orderBy('bilanmensuel.id','DESC');




        return $query->getQuery()->getResult();


    }

    /**
     * Récupère les produits en lien avec une recherche
     * @return Idmonthbm[]
     */
    public function ownprojet(int $projet) : array
    {

        $query = $this
            ->createQueryBuilder('idmonth');

        $query->innerJoin('App\Entity\Bilanmensuel', 'bilanmensuel', 'WITH', 'idmonth.id = bilanmensuel.idmonthbm')
        ;
        $query->innerJoin('App\Entity\Projet', 'projet', 'WITH', 'bilanmensuel.projet=projet.id')
        ;
        $query = $query
            ->andWhere('projet.id =:po')
            ->setParameter('po', $projet);
return $query->getQuery()->getResult();


    }


    /**
     * Récupère les produits en lien avec une recherche
     * @return Idmonthbm
     */
    public function ownmonth(int $month,int$year)
    {

        $query = $this
            ->createQueryBuilder('bilanmensuel');
        $query = $query
                ->andwhere('MONTH(bilanmensuel.monthyear) = :monti')
                ->setParameter('monti', $month);
            $query = $query
                ->andwhere('YEAR(bilanmensuel.monthyear) =:yeari')
                ->setParameter('yeari', $year);
       return $query->getQuery()->getOneOrNullResult();


    }

    /**
     * Récupère les produits en lien avec une recherche
     * @return Idmonthbm
     */
    public function ownmonthfournisseur(int $month,int$year, int $fournisseur)
    {

        $query = $this
            ->createQueryBuilder('bilanmensuel');
        $query = $query
            ->andwhere('MONTH(bilanmensuel.monthyear) = :monti')
            ->setParameter('monti', $month);
        $query = $query
            ->andwhere('YEAR(bilanmensuel.monthyear) =:yeari')
            ->setParameter('yeari', $year);
        $query = $query
            ->andwhere('bilanmensuel.fournisseur =:fo')
            ->setParameter('fo', $fournisseur);
        return $query->getQuery()->getOneOrNullResult();


    }


}
