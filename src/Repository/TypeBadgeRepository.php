<?php

namespace App\Repository;

use App\Entity\TypeBadge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeBadge|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeBadge|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeBadge[]    findAll()
 * @method TypeBadge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeBadgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeBadge::class);
    }

    // /**
    //  * @return TypeBadge[] Returns an array of TypeBadge objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeBadge
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function listeTypeBadge(){
        $queryBuilder = $this->createQueryBuilder('tb')
            ->select('tb.id,tb.libelle');
        return $queryBuilder->getQuery()
            ->getResult();
    }
}
