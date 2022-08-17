<?php

namespace App\Repository;

use App\Entity\ObjetBadge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ObjetBadge>
 *
 * @method ObjetBadge|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObjetBadge|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObjetBadge[]    findAll()
 * @method ObjetBadge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjetBadgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjetBadge::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ObjetBadge $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ObjetBadge $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return ObjetBadge[] Returns an array of ObjetBadge objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ObjetBadge
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
