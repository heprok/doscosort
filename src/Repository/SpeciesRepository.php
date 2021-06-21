<?php

namespace App\Repository;

use App\Entity\Species;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\SpeciesRepository as BaseSpeciesRepository;

/**
 * @method Species|null find($id, $lockMode = null, $lockVersion = null)
 * @method Species|null findOneBy(array $criteria, array $orderBy = null)
 * @method Species[]    findAll()
 * @method Species[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpeciesRepository extends BaseSpeciesRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = Species::class;
        parent::__construct($registry);
    }

    // /**
    //  * @return Species[] Returns an array of Species objects
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
    public function findOneBySomeField($value): ?Species
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
