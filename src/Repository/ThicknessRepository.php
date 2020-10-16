<?php

namespace App\Repository;

use App\Entity\Thickness;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Thickness|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thickness|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thickness[]    findAll()
 * @method Thickness[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThicknessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thickness::class);
    }

    // /**
    //  * @return Thickness[] Returns an array of Thickness objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Thickness
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
