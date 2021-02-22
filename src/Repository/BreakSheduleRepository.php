<?php

namespace App\Repository;

use App\Entity\BreakShedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BreakShedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method BreakShedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method BreakShedule[]    findAll()
 * @method BreakShedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BreakSheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BreakShedule::class);
    }

    // /**
    //  * @return BreakShedule[] Returns an array of BreakShedule objects
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
    public function findOneBySomeField($value): ?BreakShedule
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
