<?php

namespace App\Repository;

use App\Entity\EtatMateriel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EtatMateriel|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtatMateriel|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtatMateriel[]    findAll()
 * @method EtatMateriel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtatMaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtatMateriel::class);
    }

    // /**
    //  * @return EtatMateriel[] Returns an array of EtatMateriel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EtatMateriel
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
