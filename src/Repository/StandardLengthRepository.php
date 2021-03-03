<?php

namespace App\Repository;

use App\Entity\StandardLength;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StandardLength|null find($id, $lockMode = null, $lockVersion = null)
 * @method StandardLength|null findOneBy(array $criteria, array $orderBy = null)
 * @method StandardLength[]    findAll()
 * @method StandardLength[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StandardLengthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StandardLength::class);
    }

    // /**
    //  * @return StandardLength[] Returns an array of StandardLength objects
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
    public function findOneBySomeField($value): ?StandardLength
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
