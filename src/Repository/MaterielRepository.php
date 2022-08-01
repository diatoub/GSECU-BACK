<?php

namespace App\Repository;

use App\Entity\Materiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Materiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Materiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Materiel[]    findAll()
 * @method Materiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiel::class);
    }

    public function getMaterielBySignalisation($typeMateriel, $site)
    {
        $query = $this->createQueryBuilder('q')
            ->where('q.typeMateriel =:typeMateriel')
            ->andWhere('q.site =:site')
            ->setParameters(array(
                'typeMateriel' => $typeMateriel,
                'site'		   => $site
            ))
            ->getQuery()
            ->getOneOrNullResult();

        return $query;
    }

    public function getSumQuantiteMateriel($id, $etat)
    {
        $query = $this->createQueryBuilder('q')
            ->select('SUM(q.quantite) as quantite')
            ->where('q.id =:id')
            ->andWhere('q.etat =:etat')
            ->setParameters(array(
                'id' 	=> $id,
                'etat' => $etat
            ))
            ->getQuery()
            ->getResult();

        return $query;
    }

    // /**
    //  * @return Materiel[] Returns an array of Materiel objects
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
    public function findOneBySomeField($value): ?Materiel
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
