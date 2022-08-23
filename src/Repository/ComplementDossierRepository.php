<?php

namespace App\Repository;

use App\Entity\ComplementDossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ComplementDossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComplementDossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComplementDossier[]    findAll()
 * @method ComplementDossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplementDossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComplementDossier::class);
    }

    public function getCompl($categorie)
    {
        $query = $this->createQueryBuilder('q')
            ->select('q.id, q.libelle as libelle, s.libelle as structure, q.dateAjout as dateAjout, e.libelle as libelleetat, t.nbreJoursLivraison as nbreJoursLivraison')
            ->join('q.typeDossier', 't')
            ->join('q.structureAffecte', 's')
            ->join('q.etat', 'e')
            ->join('t.categorieDossier', 'c')
            ->where('c.code = :code')
            ->setParameter( 'code', $categorie)
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function getComplementByDossier($id) {
        $query = $this->createQueryBuilder('c')
            ->select('c.id, c.libelle, c.path')
            ->innerJoin('c.dossier', 'd')
            ->where('d.id = :id')
            ->setParameter('id', $id);
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return ComplementDossier[] Returns an array of ComplementDossier objects
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
    public function findOneBySomeField($value): ?ComplementDossier
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
