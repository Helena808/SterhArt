<?php

namespace App\Repository;

use App\Entity\Renewal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use \PDO;
/**
 * @method Renewal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Renewal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Renewal[]    findAll()
 * @method Renewal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RenewalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Renewal::class);
    }


// Вытаскиваем все stageid из Renewal - лишний
    public function findAllStages(): array
    {     
        $qb = $this->createQueryBuilder('p')
            ->select('IDENTITY(p.stageID)')
            ->orderBy('p.id', 'ASC');
        $query = $qb->getQuery();
        return $query->getArrayResult(); 
    }

// Вытаскиваем последний id из Renewal по stageID
    public function findLastRenewal($value) 
    {
        $qb = $this->createQueryBuilder('r')
            ->select('MAX(r.id)')
            ->where('r.stageID = :val')
            ->setParameter('val', $value);
        
            
        $query = $qb->getQuery();
        return $query->execute();
    }


    /**
      * @return Stage[] 
      */
    public function findByStageID($stageID)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.stageID = :val')
            ->setParameter('val', $stageID)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Renewal[] Returns an array of Renewal objects
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
    public function findOneBySomeField($value): ?Renewal
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    */



}
