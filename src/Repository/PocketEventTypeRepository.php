<?php

namespace App\Repository;

use App\Entity\PocketEventType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PocketEventType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PocketEventType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PocketEventType[]    findAll()
 * @method PocketEventType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PocketEventTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PocketEventType::class);
    }

    // /**
    //  * @return PocketEventType[] Returns an array of PocketEventType objects
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
    public function findOneBySomeField($value): ?PocketEventType
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
