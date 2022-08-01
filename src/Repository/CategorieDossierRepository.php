<?php

namespace App\Repository;

use App\Entity\CategorieDossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieDossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieDossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieDossier[]    findAll()
 * @method CategorieDossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieDossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieDossier::class);
    }

    // /**
    //  * @return CategorieDossier[] Returns an array of CategorieDossier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieDossier
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
