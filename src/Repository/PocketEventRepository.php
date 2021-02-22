<?php

namespace App\Repository;

use App\Entity\PocketEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PocketEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method PocketEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method PocketEvent[]    findAll()
 * @method PocketEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PocketEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PocketEvent::class);
    }

    // /**
    //  * @return PocketEvent[] Returns an array of PocketEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PocketEvent
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
