<?php

namespace App\Repository;

use App\Entity\NiveauAcces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NiveauAcces|null find($id, $lockMode = null, $lockVersion = null)
 * @method NiveauAcces|null findOneBy(array $criteria, array $orderBy = null)
 * @method NiveauAcces[]    findAll()
 * @method NiveauAcces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NiveauAccesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NiveauAcces::class);
    }

    // /**
    //  * @return NiveauAcces[] Returns an array of NiveauAcces objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NiveauAcces
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
