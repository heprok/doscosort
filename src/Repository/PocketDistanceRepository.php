<?php

namespace App\Repository;

use App\Entity\PocketDistance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PocketDistance|null find($id, $lockMode = null, $lockVersion = null)
 * @method PocketDistance|null findOneBy(array $criteria, array $orderBy = null)
 * @method PocketDistance[]    findAll()
 * @method PocketDistance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PocketDistanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PocketDistance::class);
    }

    // /**
    //  * @return PocketDistance[] Returns an array of PocketDistance objects
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
    public function getLastPocket(): PocketDistance
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.pocket', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
