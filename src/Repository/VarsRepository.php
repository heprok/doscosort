<?php

namespace App\Repository;

use App\Entity\Vars;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vars|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vars|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vars[]    findAll()
 * @method Vars[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VarsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vars::class);
    }

    // /**
    //  * @return Vars[] Returns an array of Vars objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findOneByName(string $name): ?Vars
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.name = :val')
            ->setParameter('val', $name)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
