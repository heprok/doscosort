<?php

namespace App\Repository;

use App\Entity\Unload;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Unload|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unload|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unload[]    findAll()
 * @method Unload[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnloadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unload::class);
    }

    // /**
    //  * @return Unload[] Returns an array of Unload objects
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
    public function findOneBySomeField($value): ?Unload
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
