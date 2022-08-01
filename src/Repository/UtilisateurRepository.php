<?php

namespace App\Repository;

use App\Entity\Profil;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    // /**
    //  * @return Utilisateur[] Returns an array of Utilisateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Utilisateur
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getAdmin(){
        $query = $this->createQueryBuilder('q')
            ->join('q.profil', 'p')
            ->where('p.code = :code')
            ->setParameter( 'code', Profil::ROLE_ADMINISTRATEUR)
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function getUserByDossier($id, $code) {
        $query = $this->createQueryBuilder('u')
            ->innerJoin('u.dossier', 'd')
            ->leftJoin('u.profil', 'p')
            ->where('d.id = :id')
            ->andWhere('p.code = :code')
            ->setParameters(array(
                'id' => $id,
                'code' => $code
            ))
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function getUserByProfil($code) {
        $query = $this->createQueryBuilder('u')
            ->leftJoin('u.profil', 'p')
            ->where('p.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function getUserDesactive()
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.enabled = :etat')
            ->setParameter('etat', false);
        return $qb->getQuery()
            ->getResult();
    }
}


