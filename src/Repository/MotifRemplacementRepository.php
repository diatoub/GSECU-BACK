<?php

namespace App\Repository;

use App\Entity\MotifRemplacement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MotifRemplacement|null find($id, $lockMode = null, $lockVersion = null)
 * @method MotifRemplacement|null findOneBy(array $criteria, array $orderBy = null)
 * @method MotifRemplacement[]    findAll()
 * @method MotifRemplacement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MotifRemplacementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MotifRemplacement::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(MotifRemplacement $entity, bool $flush = true): void
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
    public function remove(MotifRemplacement $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return MotifRemplacement[] Returns an array of MotifRemplacement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MotifRemplacement
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
