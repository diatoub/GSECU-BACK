<?php

namespace App\Repository;

use App\Entity\Equipement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Equipement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipement[]    findAll()
 * @method Equipement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipement::class);
    }

    // /**
    //  * @return Equipement[] Returns an array of Equipement objects
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
    public function findOneBySomeField($value): ?Equipement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function listEquipements($limit,$offset,$filtre){
        $queryBuilder = $this->createQueryBuilder('e')
            ->select('e.id,e.libelle,e.description');
        if($limit != 'ALL'){
            $queryBuilder->setFirstResult($offset)->setMaxResults($limit);
        }
        if($filtre != ''){
            $queryBuilder
                ->andWhere('e.libelle LIKE :filtre')
                ->setParameter('filtre','%'.$filtre.'%');
        }
        return $queryBuilder->getQuery()
            ->getResult();
    }

    public function countEquipements($filtre){
        $queryBuilder= $this->createQueryBuilder('e')
            ->select('count(e.id)');
        if($filtre != ''){
            $queryBuilder
                ->andWhere('e.libelle LIKE :filtre')
                ->setParameter('filtre','%'.$filtre.'%');
        }
        return $queryBuilder->getQuery()
            ->getSingleScalarResult();
    }
}
