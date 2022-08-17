<?php

namespace App\Repository;

use App\Entity\MotifDemande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MotifDemande|null find($id, $lockMode = null, $lockVersion = null)
 * @method MotifDemande|null findOneBy(array $criteria, array $orderBy = null)
 * @method MotifDemande[]    findAll()
 * @method MotifDemande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MotifDemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MotifDemande::class);
    }

    // /**
    //  * @return MotifDemande[] Returns an array of MotifDemande objects
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
    public function findOneBySomeField($value): ?MotifDemande
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function lesMotifsDemandes($type){
        $queryBuilder = $this->createQueryBuilder('md')
            ->select('md.id, md.libelle, md.docsACharger, md.etat');
            if ($type!='ALL'){
                $queryBuilder->andWhere('md.etat = :etat')->setParameter('etat',(int)$type);
            }
        return $queryBuilder->getQuery()
            ->getResult();
    }
}
