<?php

namespace App\Repository;

use App\Entity\Sketch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Sketch|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sketch|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sketch[]    findAll()
 * @method Sketch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SketchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sketch::class);
    }

    // /**
    //  * @return Sketch[] Returns an array of Sketch objects
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
    public function findOneBySomeField($value): ?Sketch
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
