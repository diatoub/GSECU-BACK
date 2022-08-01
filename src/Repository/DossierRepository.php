<?php

namespace App\Repository;

use App\Entity\Dossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dossier[]    findAll()
 * @method Dossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dossier::class);
    }

    // /**
    //  * @return Dossier[] Returns an array of Dossier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dossier
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getLastDossierId($limit = 1){
        return $this->createQueryBuilder('q')
            ->select('q.id as last_id')
            ->orderBy('q.dateAjout', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getTotauxByDossier($categorie, $entity)
    {
        $query = $this->createQueryBuilder('q')
            ->select('t.id as type_id, t.libelle as libelle_type, COUNT(t) as percentage')
            ->join('q.typeDossier', 't')
            ->join('q.etat', 'e')
            ->join('q.site', 's')
            ->join('t.categorieDossier', 'c')
            ->where('c.code = :code')
            ->setParameter('code', $categorie);
        if($entity->getSite())
        {
            $query->andWhere('q.site = :site_id')
                ->setParameter('site_id', $entity->getSite()->getId());
        }
        if($entity->getEtat())
        {
            $query->andWhere('q.etat = :etat_id')
                ->setParameter('etat_id', $entity->getEtat()->getId());
        }
        if($entity->getDateDebut() && $entity->getDateFin())
        {
            $query->andWhere('q.dateAjout BETWEEN :dateDebut AND :dateFin')
                ->setParameter('dateDebut', $entity->getDateDebut()->format('Y-m-d'))
                ->setParameter('dateFin', $entity->getDateFin()->format('Y-m-d'));
        }
        $query->groupBy('t.id');

        return $query->getQuery()->getResult();
    }

    public function getTotauxBySite($categorie, $entity)
    {
        $query = $this->createQueryBuilder('q')
            ->select('s.id as site_id, s.libelle as libelle_site, COUNT(q) as percentage')
            ->join('q.typeDossier', 't')
            ->join('q.etat', 'e')
            ->join('q.site', 's')
            ->join('t.categorieDossier', 'c')
            ->where('c.code = :code')
            ->setParameter('code', $categorie);
        if($entity->getEtat())
        {
            $query->andWhere('q.etat = :etat_id')
                ->setParameter('etat_id', $entity->getEtat()->getId());
        }
        if($entity->getDateDebut() && $entity->getDateFin())
        {
            $query->andWhere('q.dateAjout BETWEEN :dateDebut AND :dateFin')
                ->setParameter('dateDebut', $entity->getDateDebut()->format('Y-m-d'))
                ->setParameter('dateFin', $entity->getDateFin()->format('Y-m-d'));
        }
        $query->groupBy('s.id');

        return $query->getQuery()->getResult();
    }

    public function getAllDossier($categorie, $entity)
    {
        $query = $this->createQueryBuilder('q')
            ->select('q.id, q.codeSecret as codeSecret, t.libelle as libelle_type, s.libelle as site, q.libelle as libelle, q.dateAjout as dateAjout, e.libelle as libelleetat, t.nbreJoursLivraison as nbreJoursLivraison')
            ->join('q.typeDossier', 't')
            ->join('q.etat', 'e')
            ->join('q.site', 's')
            ->join('t.categorieDossier', 'c')
            ->where('c.code = :code')
            ->setParameter( 'code', $categorie)
            ->orderBy('q.id', 'DESC')
            ;

        if($entity->getLibelle())
        {
            $query->andWhere('q.libelle LIKE :libelle')
                ->setParameter('libelle', '%'.$entity->getLibelle().'%');
        }
        if($entity->getCodeDossier())
        {
            $query->andWhere('q.codeDossier = :codeDossier')
                ->setParameter('codeDossier', $entity->getCodeDossier());
        }
        if($entity->getSite())
        {
            $query->andWhere('q.site = :site_id')
                ->setParameter('site_id', $entity->getSite()->getId());
        }
        if($entity->getTypeDossier())
        {
            $query->andWhere('q.typeDossier = :type_dossier_id')

                ->setParameter('type_dossier_id', $entity->getTypeDossier()->getId());
        }
        if($entity->getEtat())
        {
            $query->andWhere('q.etat = :etat_id')
                ->setParameter('etat_id', $entity->getEtat()->getId());
        }

        if($entity->getDateDebut() && $entity->getDateFin())
        {
            $query->andWhere('q.dateAjout BETWEEN :dateDebut AND :dateFin')
                ->setParameter('dateDebut', $entity->getDateDebut()->format('Y-m-d'))
                ->setParameter('dateFin', $entity->getDateFin()->format('Y-m-d'));
        }

        return $query->getQuery()->getResult();
    }

    public function getLastDosssierId()
    {
        $query = $this->createQueryBuilder('q')
            ->select('q.id as id')
            ->orderBy('id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $query;
    }

    public function getAllDossierByUser($user_id, $categorie)
    {
        $query = $this->createQueryBuilder('q')
            ->select('q.id as id, q.codeSecret as codeSecret, t.libelle as libelle_type, s.libelle as site, q.libelle as libelle, q.dateAjout as dateAjout, e.libelle as libelleetat, t.nbreJoursLivraison as nbreJoursLivraison')
            ->innerJoin('q.utilisateur', 'u')
            ->join('q.typeDossier', 't')
            ->join('q.site', 's')
            ->join('q.etat', 'e')
            ->join('t.categorieDossier', 'c')
            ->where('u.id = :id')
            ->andWhere('c.code = :code')
            ->setParameters(array(
                'id'   => $user_id,
                'code' => $categorie ))
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function getDossierUserByProfil($id, $code)
    {
        $query = $this->createQueryBuilder('q')
            ->innerJoin('q.utilisateur', 'u')
            ->leftJoin('u.profil', 'p')
            ->where('q.id = :id')
            ->andWhere('p.code = :code')
            ->setParameters(array(
                'id'   => $id,
                'code' => $code	))
            ->getQuery()
            ->getOneOrNullResult();
        return $query;
    }

    public function getDateLivraison($id)
    {
        $query = $this->createQueryBuilder('q')
            ->select('DATE_ADD(q.dateAjout, INTERVAL 5 DAY)')
            ->leftJoin('q.typeDossier', 't')
            ->where('q.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function getAllDossierEtatByUser($user_id, $categorie, $etat)
    {
        $query = $this->createQueryBuilder('q')
            ->innerJoin('q.utilisateur', 'u')
            ->join('q.etat', 'e')
            ->join('q.typeDossier', 't')
            ->join('t.categorieDossier', 'c')
            ->where('u.id = :id')
            ->andWhere('c.code = :code')
            ->andWhere('e.libelle = :libelle')
            ->setParameters(array(
                'id'   => $user_id,
                'code' => $categorie,
                'libelle' => $etat ))
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function getAllDossierEtat($categorie, $etat)
    {
        $query = $this->createQueryBuilder('q')
            ->join('q.etat', 'e')
            ->join('q.typeDossier', 't')
            ->join('t.categorieDossier', 'c')
            ->where('c.code = :code')
            ->andWhere('e.libelle = :libelle')
            ->setParameters(array(
                'code' => $categorie,
                'libelle' => $etat ))
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function getDossierByMonth($categorie)
    {
        $current_date = new \DateTime();
        $begin_date = new \DateTime();
        $begin_date->modify('-12 month');
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $config->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $config->addCustomDatetimeFunction('MONTHNAME', 'DoctrineExtensions\Query\Mysql\MonthName');

        $query = $this->createQueryBuilder('q')
            ->select('t.id as type_id, t.libelle as type_libelle, MONTHNAME(q.dateAjout) as mois,  YEAR(q.dateAjout) as annee, count(q.id) as nombre')
            ->leftJoin('q.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('c.code = :code')
            ->andWhere('q.dateAjout >= :date_debut and q.dateAjout <= :date_courant')
            ->groupBy('mois, type_id')
            ->orderBy('annee, mois', 'ASC')
            ->setParameters(array(
                'date_debut' => $begin_date->format('Y-m-d'),
                'date_courant' => $current_date->format('Y-m-d'),
                'code' => $categorie
            ))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getCountByMonth()
    {
        $current_date = new \DateTime();
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $query = $this->createQueryBuilder('q')
            ->select('count(q) as total')
            ->where('MONTH(q.dateAjout) = :current_month')
            ->setParameters(array(
                'current_month' => $current_date->format('m')
            ));
        return $query->getQuery()->getOneOrNullResult();
    }


    public function getDossierUserByMonth($user_id, $categorie)
    {
        $current_date = new \DateTime();
        $begin_date = new \DateTime();
        $begin_date->modify('-12 month');
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $config->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $config->addCustomDatetimeFunction('MONTHNAME', 'DoctrineExtensions\Query\Mysql\MonthName');

        $query = $this->createQueryBuilder('q')
            ->select('t.id as type_id, t.libelle as type_libelle, MONTHNAME(q.dateAjout) as mois,  YEAR(q.dateAjout) as annee, count(q.id) as nombre')
            ->innerJoin('q.utilisateur', 'u')
            ->leftJoin('q.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('c.code = :code')
            ->andWhere('u.id = :id')
            ->andWhere('q.dateAjout >= :date_debut and q.dateAjout <= :date_courant')
            ->groupBy('mois, type_id')
            ->orderBy('annee, mois', 'ASC')
            ->setParameters(array(
                'date_debut' => $begin_date->format('Y-m-d'),
                'date_courant' => $current_date->format('Y-m-d'),
                'code' => $categorie,
                'id'   => $user_id
            ))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getCountDossierByType($categorie)
    {
        $query = $this->createQueryBuilder('q')
            ->select('t.id as type_id, t.libelle as type_libelle, count(q.id) as nombre')
            ->leftJoin('q.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('c.code = :code')
            ->groupBy('type_id')
            ->setParameters(array('code' => $categorie))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getCountDossierUserByType($user_id, $categorie)
    {
        $query = $this->createQueryBuilder('q')
            ->select('t.id as type_id, t.libelle as type_libelle, count(q.id) as nombre')
            ->innerJoin('q.utilisateur', 'u')
            ->leftJoin('q.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('c.code =:code')
            ->andWhere('u.id =:id')
            ->groupBy('type_id')
            ->setParameters(array('code' => $categorie,
                'id'  => $user_id))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getDossierCategorieByMonth()
    {
        $current_date = new \DateTime();
        $begin_date = new \DateTime();
        $begin_date->modify('-12 month');
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $config->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $config->addCustomDatetimeFunction('MONTHNAME', 'DoctrineExtensions\Query\Mysql\MonthName');

        $query = $this->createQueryBuilder('q')
            ->select('c.id as categorie_id, c.libelle as categorie_libelle, MONTHNAME(q.dateAjout) as mois,  YEAR(q.dateAjout) as annee, count(q.id) as nombre')
            ->leftJoin('q.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('q.dateAjout >= :date_debut and q.dateAjout <= :date_courant')
            ->groupBy('mois, categorie_id')
            ->orderBy('annee, mois', 'ASC')
            ->setParameters(array(
                'date_debut' => $begin_date->format('Y-m-d'),
                'date_courant' => $current_date->format('Y-m-d')
            ))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getDossierCategorieByUser($user_id)
    {
        $current_date = new \DateTime();
        $begin_date = new \DateTime();
        $begin_date->modify('-12 month');
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $config->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $config->addCustomDatetimeFunction('MONTHNAME', 'DoctrineExtensions\Query\Mysql\MonthName');

        $query = $this->createQueryBuilder('q')
            ->select('c.id as categorie_id, c.libelle as categorie_libelle, MONTHNAME(q.dateAjout) as mois,  YEAR(q.dateAjout) as annee, count(q.id) as nombre')
            ->innerJoin('q.utilisateur', 'u')
            ->leftJoin('q.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('u.id =:user_id')
            ->andWhere('q.dateAjout >= :date_debut and q.dateAjout <= :date_courant')
            ->groupBy('mois, categorie_id')
            ->orderBy('annee, mois', 'ASC')
            ->setParameters(array(
                'date_debut' => $begin_date->format('Y-m-d'),
                'date_courant' => $current_date->format('Y-m-d'),
                'user_id' => $user_id
            ))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getCountAllDossierByCategorie()
    {
        $query = $this->createQueryBuilder('q')
            ->select('c.id as categorie_id, c.libelle as categorie_libelle, count(q.id) as nombre')
            ->leftJoin('q.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->groupBy('categorie_id')
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getCountAllUserDossierByCategorie($user_id)
    {
        $query = $this->createQueryBuilder('q')
            ->select('c.id as categorie_id, c.libelle as categorie_libelle, count(q.id) as nombre')
            ->innerJoin('q.utilisateur', 'u')
            ->leftJoin('q.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('u.id =:id')
            ->groupBy('categorie_id')
            ->setParameters(array('id'  => $user_id))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getNombreJourRequis($id)
    {
        $query = $this->createQueryBuilder('q')
            ->select('t.nbreJoursLivraison')
            ->leftJoin('q.typeDossier', 't')
            ->where('q.id =:id')
            ->setParameters(array('id'  => $id))
            ->getQuery()
            ->getOneOrNullResult();
        return $query;
    }

    public function getAllDossierByEtat($id)
    {
        $query = $this->createQueryBuilder('q')
            ->select('q.id, q.libelle, q.dateAjout, s.libelle as structure_affecte')
            ->leftJoin('q.etat', 'e')
            ->leftJoin('q.structureAffecte', 's')
            ->where('e.id =:id')
            ->setParameters(array('id'  => $id))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getAllDossierUserProfilByEtat($profil, $libelle_etat)
    {
        $query = $this->createQueryBuilder('q')
            ->select('q.id, q.libelle, q.dateAjout, u.nom as user_nom, u.prenom as user_prenom, u.email as user_email')
            ->innerJoin('q.utilisateur', 'u')
            ->leftJoin('u.profil', 'p')
            ->leftJoin('q.etat', 'e')
            ->where('e.libelle =:libelle')
            ->andWhere('p.code =:profil')
            ->setParameters(array('libelle'  => $libelle_etat, 'profil' => $profil))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getCoutDossier($categorie, $entity)
    {
        $query = $this->createQueryBuilder('q')
            ->select('q.id as dossier_id, q.coutDossier as cout_dossier, q.libelle as libelle_dossier')
            ->leftJoin('q.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->leftJoin('q.etat', 'e')
            ->where('c.code = :code')
            ->setParameter('code', $categorie);
        if($entity->getTypeDossier())
        {
            $query->andWhere('q.typeDossier = :type_dossier_id')
                ->setParameter('type_dossier_id', $entity->getTypeDossier()->getId());
        }
        if($entity->getEtat())
        {
            $query->andWhere('q.etat = :etat_id')
                ->setParameter('etat_id', $entity->getEtat()->getId());
        }
        if($entity->getDateDebut() && $entity->getDateFin())
        {
            $query->andWhere('q.dateAjout BETWEEN :dateDebut AND :dateFin')
                ->setParameter('dateDebut', $entity->getDateDebut()->format('Y-m-d'))
                ->setParameter('dateFin', $entity->getDateFin()->format('Y-m-d'));
        }

        return $query->getQuery()->getResult();
    }

    public function getTotauxStats()
    {
        $query = $this->createQueryBuilder('q')
            ->select('t.id as type_id, t.libelle as libelle_type, COUNT(t) as percentage')
            ->join('q.typeDossier', 't')
            ->join('q.etat', 'e')
            ->join('q.site', 's')
            ->join('t.categorieDossier', 'c')
            ;
        $query->groupBy('t.id');

        return $query->getQuery()->getResult();
    }

    public function getTotauxWithDelaisStats()
    {
        $query = $this->createQueryBuilder('q')
            ->select('t.id as type_id, t.libelle as libelle_type, COUNT(t) as percentage, SUM(case when sd.isHorsDelai is null then 1 else 0 end) as delais,
              SUM(case when sd.isHorsDelai is not null then 1 else 0 end) as horsdelais')
            ->join('q.typeDossier', 't')
            ->join('q.etat', 'e')
            ->join('q.site', 's')
            ->join('t.categorieDossier', 'c')
            ->join('q.suiviDelai', 'sd')
        ;
        $query->groupBy('t.id');

        return $query->getQuery()->getResult();
    }

    public function getTotaux()
    {
        $query = $this->createQueryBuilder('q')
            ->select('c.id as type_id, c.libelle as libelle_type, COUNT(t) as percentage, SUM(case when sd.isHorsDelai is null then 1 else 0 end) as delais,
              SUM(case when sd.isHorsDelai is not null then 1 else 0 end) as horsdelais')
            ->join('q.typeDossier', 't')
            ->join('q.etat', 'e')
            ->join('q.site', 's')
            ->join('t.categorieDossier', 'c')
            ->join('q.suiviDelai', 'sd')
        ;
        $query->groupBy('c.id');

        return $query->getQuery()->getResult();
    }


}
