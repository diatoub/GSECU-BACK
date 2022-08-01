<?php

namespace App\Repository;

use App\Entity\EtatMateriel;
use App\Entity\SuiviMateriel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SuiviMateriel|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuiviMateriel|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuiviMateriel[]    findAll()
 * @method SuiviMateriel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuiviMaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuiviMateriel::class);
    }

    // /**
    //  * @return SuiviMateriel[] Returns an array of SuiviMateriel objects
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
    public function findOneBySomeField($value): ?SuiviMateriel
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getSumQuantiteMateriel($materiel_id, $etat)
    {
        $query = $this->createQueryBuilder('q')
            ->select('SUM(q.quantite) as quantite')
            ->leftJoin('q.Materiel', 'm')
            ->leftJoin('q.etatMateriel', 'e')
            ->where('m.id =:id')
            ->andWhere('e.code =:etat')
            ->setParameters(array(
                'id' 	=> $materiel_id,
                'etat' => $etat
            ))

            ->getQuery()
            ->getSingleResult();
        return $query;
    }

    public function getHistoriqueMaterielByMonth($id)
    {
        $current_date = new \DateTime();
        $begin_date = new \DateTime();
        $begin_date->modify('-12 month');
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $config->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $config->addCustomDatetimeFunction('MONTHNAME', 'DoctrineExtensions\Query\Mysql\MonthName');

        $query = $this->createQueryBuilder('q')
            ->select('m.id as materiel_id, s.libelle as libelle_site, t.libelle as libelle_materiel, MONTHNAME(q.date) as mois, YEAR(q.date) as annee, SUM(q.quantite) as quantite_defectueuse')
            ->leftJoin('q.Materiel', 'm')
            ->leftJoin('q.etatMateriel', 'e')
            ->leftJoin('m.typeMateriel', 't')
            ->leftJoin('m.site', 's')
            ->where('m.id =:id')
            ->andWhere('q.date >= :date_debut and q.date <= :date_courant')
            ->andWhere('e.code IN (:etat_materiel)')
            ->groupBy('mois')
            ->orderBy('annee, mois', 'ASC')
            ->setParameters(array(
                'date_debut' => $begin_date->format('Y-m-d'),
                'date_courant' => $current_date->format('Y-m-d'),
                'etat_materiel' => array(EtatMateriel::MATERIEL_DEFECTUEUX, EtatMateriel::MATERIEL_REPARATION),
                'id' => $id
            ))
            ->getQuery()
            ->getArrayResult();
        return $query;
    }

    public function getQuantiteMaterielByEtat($id)
    {
        $query = $this->createQueryBuilder('q')
            ->select('m.id as materiel_id, m.quantite as total, SUM(q.quantite) as quantite, (m.quantite - SUM(q.quantite)) as fonctionnel, e.id as etat_id, e.libelle as libelle_etat, t.libelle as libelle_materiel')
            ->leftJoin('q.Materiel', 'm')
            ->leftJoin('m.typeMateriel', 't')
            ->leftJoin('q.etatMateriel', 'e')
            ->where('m.id =:id')
            ->groupBy('libelle_etat')
            ->setParameters(array('id' => $id))
            ->getQuery()
            ->getArrayResult()
        ;
        return $query;
    }

    public function getMaterielFonctionnel($materiel_id)
    {
        $query = $this->createQueryBuilder('q')
            ->leftJoin('q.Materiel', 'm')
            ->leftJoin('q.etatMateriel', 'e')
            ->where('m.id =:materiel_id')
            ->andWhere('e.code =:etat')
            ->setParameters(array('materiel_id' => $materiel_id, 'etat' => EtatMateriel::MATERIEL_FONCTIONNEL))
            ->getQuery()
            ->getSingleResult();
        return $query;
    }

    public function getQuantiteMaterielBySuivi($categorie, $entity)
    {
        $query = $this->createQueryBuilder('q')
            ->select('q.id as materiel_id, q.quantite as quantite_materiel, tm.libelle as libelle_type, s.libelle as libelle_site')
            ->leftJoin('q.Materiel', 'm')
            ->leftJoin('m.typeMateriel', 'tm')
            ->leftJoin('m.site', 's')
            ->leftJoin('q.dossier', 'd')
            ->leftJoin('d.typeDossier', 't')
            ->leftJoin('t.categorieDossier', 'c')
            ->where('c.code = :code')
            ->setParameter('code', $categorie);
        if($entity->getEtatMateriel())
        {
            $query->andWhere('q.etatMateriel = :etat_materiel_id')
                ->setParameter('etat_materiel_id', $entity->getEtatMateriel()->getId());
        }
        if($entity->getTypeMateriel())
        {
            $query->andWhere('m.typeMateriel = :type_materiel_id')
                ->setParameter('etat_materiel_id', $entity->getTypeMateriel()->getId());
        }
        if($entity->getDateDebut() && $entity->getDateFin())
        {
            $query->andWhere('q.date BETWEEN :dateDebut AND :dateFin')
                ->setParameter('dateDebut', $entity->getDateDebut()->format('Y-m-d'))
                ->setParameter('dateFin', $entity->getDateFin()->format('Y-m-d'));
        }
        return $query->getQuery()->getResult();
    }
}
