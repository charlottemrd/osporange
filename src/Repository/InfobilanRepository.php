<?php

namespace App\Repository;

use App\Entity\Infobilan;
use App\Entity\Bilanmensuel;
use App\Entity\Idmonthbm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Infobilan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Infobilan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Infobilan[]    findAll()
 * @method Infobilan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfobilanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Infobilan::class);
    }

    // /**
    //  * @return Infobilan[] Returns an array of Infobilan objects
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
    public function findOneBySomeField($value): ?Infobilan
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
     * @return Infobilan[]
     */
    public function searchinfobilandebite(int $projid) : array
    {

        $query = $this
            ->createQueryBuilder('infobilan');

        $query->innerJoin('App\Entity\Bilanmensuel', 'bilanmensuel', 'WITH', 'bilanmensuel.id = infobilan.bilanmensuel')
        ;
        $query->innerJoin('App\Entity\Idmonthbm', 'idmonthbm', 'WITH', 'idmonthbm.id=bilanmensuel.idmonthbm')
        ;
        $query = $query
            ->andWhere('idmonthbm.isaccept=:bo')
            ->setParameter('bo', true);
        $query = $query
            ->andWhere('bilanmensuel.projet=:po')
            ->setParameter('po',$projid);


         return $query->getQuery()->getResult();


    }
}
