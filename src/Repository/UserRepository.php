<?php

namespace App\Repository;

use App\Entity\Profil;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function listeAllUser($limit, $offset, $filtre) {
        $query = $this->createQueryBuilder('u')
            ->select('u.id, u.email, u.nom, u.prenom, u.telephone, u.username, s.id as idStructure, s.libelle as structure, ts.id as idTypeStructure, ts.libelle as typeStructure')
            ->innerJoin('u.structure', 's')
            ->innerJoin('s.typeStructure', 'ts')
            ->where('u.enabled = :enabled')
            ->setParameter( 'enabled', true);
            if($limit != 'ALL'){
                $query->setFirstResult($offset)->setMaxResults($limit);
            }
            if($filtre != ''){
                $query
                    ->andWhere('u.prenom LIKE :filtre OR u.nom LIKE :filtre OR u.email LIKE :filtre')
                    ->setParameter('filtre','%'.$filtre.'%');
            }

        return $query ->getQuery()->getResult();
    }

    public function countAllUser($limit, $offset, $filtre) {
        $query = $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->innerJoin('u.structure', 's')
            ->innerJoin('s.typeStructure', 'ts')
            ->where('u.enabled = :enabled')
            ->setParameter( 'enabled', true);
            if($filtre != ''){
                $query
                    ->andWhere('u.prenom LIKE :filtre OR u.nom LIKE :filtre OR u.email LIKE :filtre')
                    ->setParameter('filtre','%'.$filtre.'%');
            }
        return $query->getQuery()->getSingleScalarResult();
    }

    public function getAdmin(){
        $query = $this->createQueryBuilder('q')
            ->join('q.profil', 'p')
            ->where('p.code = :code')
            ->setParameter( 'code', Profil::ADMINISTRATEUR)
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function getUserByDossier($id, $code) {
        $query = $this->createQueryBuilder('u')
            ->select('u.id, u.email, u.nom, u.prenom, u.telephone, p.code as profil')
            ->innerJoin('u.dossier', 'd')
            ->leftJoin('u.profil', 'p')
            ->where('d.id = :id')
            ->andWhere('p.code in (:code)')
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

    public function getAdminAndExecuteur($email=null){
        $query = $this->createQueryBuilder('q')
            ->join('q.profil', 'p')
            ->where('p.code IN (:code)')->setParameter( 'code', [Profil::ADMINISTRATEUR, Profil::EXECUTEUR, Profil::DGSECU, Profil::SUPER_AGENT])
            ->andWhere('q.enabled = :etat')->setParameter('etat', true);
        $query = $email ?         
            $query->addSelect("GROUP_CONCAT(DISTINCT q.email SEPARATOR ', ') AS emailAdmin")->getQuery()->getResult() : 
            $query->getQuery()->getResult();
        return $query;
    }
}


