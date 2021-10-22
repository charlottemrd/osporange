<?php

namespace App\Repository;

use App\Entity\Bilanmensuel;
use App\Entity\Idmonthbm;
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

    /**
     * Récupère les produits en lien avec une recherche
     * @return Bilanmensuel[]
     */
    public function listebilanmensuel(Idmonthbm $idmonthbm) : array
    {

        $query = $this
            ->createQueryBuilder('bilanmensuel');

        $query->innerJoin('App\Entity\Idmonthbm', 'idmonthbm', 'WITH', 'idmonthbm.id = bilanmensuel.idmonthbm')
        ;
        $query = $query
            ->andWhere('bilanmensuel.idmonthbm =:monthbm')
            ->setParameter('monthbm', $idmonthbm);





        return $query->getQuery()->getResult();


    }





}
