<?php

namespace App\Repository;

use App\Entity\Structure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Structure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Structure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Structure[]    findAll()
 * @method Structure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StructureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Structure::class);
    }

    // /**
    //  * @return Structure[] Returns an array of Structure objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Structure
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function lesStructures($typeStructure, $type){
        $queryBuilder = $this->createQueryBuilder('s')
        ->select('s.id, s.libelle, s.rgt, s.lft, s.lvl, s.root, ts.libelle as type_structure, p.libelle as pere_libelle')
        ->innerJoin('s.typeStructure', 'ts')
        ->innerJoin('s.pere', 'p');
            if ($typeStructure!=null){
                $queryBuilder->andWhere('ts.libelle = :typeStructure')->setParameter('typeStructure',$typeStructure);
            }
        return $queryBuilder->getQuery()
            ->getResult();
    }
}
