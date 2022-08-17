<?php

namespace App\Repository;

use App\Entity\BeneficiaireQrcode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BeneficiaireQrcode>
 *
 * @method BeneficiaireQrcode|null find($id, $lockMode = null, $lockVersion = null)
 * @method BeneficiaireQrcode|null findOneBy(array $criteria, array $orderBy = null)
 * @method BeneficiaireQrcode[]    findAll()
 * @method BeneficiaireQrcode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeneficiaireQrcodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BeneficiaireQrcode::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(BeneficiaireQrcode $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(BeneficiaireQrcode $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getBeneficiaireQrcodeByDossier($id) {
        $query = $this->createQueryBuilder('u')
            ->innerJoin('u.dossier', 'd')
            ->where('d.id = :id')
            ->andWhere('u.sendQrcode = :etat')
            ->setParameters(array(
                'etat' => true,
                'id' => $id
            ))
            ->getQuery()
            ->getResult();
        return $query;
    }

    // /**
    //  * @return BeneficiaireQrcode[] Returns an array of BeneficiaireQrcode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BeneficiaireQrcode
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
