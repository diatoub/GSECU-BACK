<?php

namespace App\Repository;

use App\Entity\SuiviDelai;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SuiviDelai|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuiviDelai|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuiviDelai[]    findAll()
 * @method SuiviDelai[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuiviDelaiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuiviDelai::class);
    }



    // /**
    //  * @return SuiviDelai[] Returns an array of SuiviDelai objects
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
    public function findOneBySomeField($value): ?SuiviDelai
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function getAllHorsDelaiByType($categorie)
    {
        $query = $this->createQueryBuilder('q')
            ->select('t.id as type_id, t.libelle as libelle_type, c.id as categorie_id, count(q.id) as nombre')
            ->leftJoin('q.dossier', 'd')
            ->leftJoin('d.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('c.code =:code')
            ->andWhere('q.isHorsDelai =:horsdelai')
            ->groupBy('type_id')
            ->setParameters(array('code' =>$categorie,
                'horsdelai' =>true))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }


    public function getAllHorsDelaiUserByType($user_id, $categorie)
    {
        $query = $this->createQueryBuilder('q')
            ->select('t.id as type_id, t.libelle as libelle_type, c.id as categorie_id, count(q.id) as nombre')
            ->leftJoin('q.dossier', 'd')
            ->innerJoin('d.user', 'u')
            ->leftJoin('d.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('c.code =:code')
            ->andWhere('u.id =:user_id')
            ->andWhere('q.isHorsDelai =:horsdelai')
            ->groupBy('type_id')
            ->setParameters(array('code' =>$categorie,
                'user_id' => $user_id,
                'horsdelai' =>true))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getAllHorsDelaiByCategorie()
    {
        $query = $this->createQueryBuilder('q')
            ->select('c.id as categorie_id, c.libelle as libelle_categorie, count(q.id) as nombre')
            ->leftJoin('q.dossier', 'd')
            ->leftJoin('d.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->andWhere('q.isHorsDelai =:horsdelai')
            ->groupBy('categorie_id')
            ->setParameters(array('horsdelai' =>true))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getAllHorsDelaiUserByCategorie($user_id)
    {
        $query = $this->createQueryBuilder('q')
            ->select('c.id as categorie_id, c.libelle as libelle_categorie, count(q.id) as nombre')
            ->leftJoin('q.dossier', 'd')
            ->innerJoin('d.user', 'u')
            ->leftJoin('d.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('q.isHorsDelai =:horsdelai')
            ->groupBy('categorie_id')
            ->setParameters(array('user_id' => $user_id, 'horsdelai' =>true))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getAllByDelai($categorie, $entity)
    {
        $query = $this->createQueryBuilder('q')
            ->select('d.id as dossier_id, q.interval as nombre_jour, d.libelle as libelle_dossier, t.libelle as libelle_type')
            ->leftJoin('q.dossier', 'd')
            ->leftJoin('d.etat', 'e')
            ->leftJoin('d.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('c.code = :code')
            ->setParameter('code', $categorie);
        if($entity->getEtat())
        {
            $query->andWhere('q.etat = :etat_id')
                ->setParameter('etat_id', $entity->getEtat()->getId());
        }
        if($entity->getTypeDossier())
        {
            $query->andWhere('q.typeDossier = :type_dossier_id')

                ->setParameter('type_dossier_id', $entity->getTypeDossier()->getId());
        }
        if($entity->getDateDebut() && $entity->getDateFin())
        {
            $query->andWhere('d.dateAjout BETWEEN :dateDebut AND :dateFin')
                ->setParameter('dateDebut', $entity->getDateDebut()->format('Y-m-d'))
                ->setParameter('dateFin', $entity->getDateFin()->format('Y-m-d'));
        }

        return $query->getQuery()->getResult();
    }

    public function getTotauxByDelai($categorie, $entity)
    {
        $query = $this->createQueryBuilder('q')
            ->select('d.id as dossier_id, q.interval as nombre_jour, d.libelle as libelle_dossier, t.libelle as libelle_type, count(q.id) as nombre')
            ->leftJoin('q.dossier', 'd')
            ->leftJoin('d.etat', 'e')
            ->leftJoin('d.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('c.code = :code')
            ->setParameter('code', $categorie);


        return $query->groupBy('t.id')->getQuery()->getResult();
    }

    public function getTotauxHorsDelai($categorie, $entity)
    {
        $query = $this->createQueryBuilder('q')
            ->select('d.id as dossier_id, d.libelle as libelle_dossier, t.id as type_id, t.libelle as libelle_type, c.id as categorie_id, q.interval as nombre_jour,
             count(q.id) as nombre')
            ->leftJoin('q.dossier', 'd')
            ->leftJoin('d.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('c.code =:code')
            ->andWhere('q.isHorsDelai =:horsdelai')
            ->groupBy('type_id')
            ->setParameters(array('code' =>$categorie,
                'horsdelai' =>true));


        return $query->groupBy('t.id')->getQuery()->getResult();
    }

    public function getDemandesDelai($categorie)
    {
        $query = $this->createQueryBuilder('q')
            ->select('c.id as dossier_id, count(q.id) as nombre, count(q.interval) as nombre_jour')
            //->select('d.id as dossier_id, q.interval as nombre_jour, d.libelle as libelle_dossier, t.libelle as libelle_type, count(q.id) as nombre')
            ->leftJoin('q.dossier', 'd')
            ->leftJoin('d.etat', 'e')
            ->leftJoin('d.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('c.code = :code')
            ->setParameter('code', $categorie);
            //->groupBy('c.id'); //Ajouté

        return $query->groupBy('c.id')->getQuery()->getResult();
    }

    public function getDemandesHorsDelai($categorie)
    {
        $query = $this->createQueryBuilder('q')
            ->select('c.id as dossier_id, count(q.id) as nombre, count(q.interval) as nombre_jour')
            //->select('d.id as dossier_id, d.libelle as libelle_dossier, q.interval as nombre_jour, count(q.id) as nombre')
            ->leftJoin('q.dossier', 'd')
            ->leftJoin('d.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('c.code =:code')
            ->andWhere('q.isHorsDelai =:horsdelai')
            ->setParameters(array('code' =>$categorie, 'horsdelai' =>true));
            //->groupBy('type_id');
            //->groupBy('d.id');

        return $query->groupBy('c.id')->getQuery()->getResult();
    }

}
