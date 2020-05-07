<?php

namespace App\Repository;

use App\Entity\Stage;
use App\Entity\Project;
use App\Entity\Renewal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Stage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stage[]    findAll()
 * @method Stage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stage::class);
    }

// ДЛЯ ИСПОЛНИТЕЛЯ
    /**
      * @return Stage[] 
      */
    public function findByProjectID($projectID)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.projectID = :val')
            ->setParameter('val', $projectID)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
      * @return Stage[] 
      */
    public function findActiveStages()
    {
        return $this->createQueryBuilder('s')
            ->innerJoin(Project::class, 'p', 'with', 's.projectID = p.id')
            ->innerJoin(Renewal::class, 'r', 'with', 'r.stageID = s.id')
            ->andWhere("s.status = 'в работе'")
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

// ДЛЯ КЛИЕНТА
    public function findActiveByProjectID($projectID)
    {
        return $this->createQueryBuilder('s')
            ->andWhere("s.status = 'в работе'")
            ->andWhere('s.projectID = :val')
            ->setParameter('val', $projectID)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Stage[] Returns an array of Stage objects
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
    public function findOneBySomeField($value): ?Stage
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
