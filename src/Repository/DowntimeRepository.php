<?php

namespace App\Repository;

use App\Entity\Downtime;
use DatePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Downtime|null find($id, $lockMode = null, $lockVersion = null)
 * @method Downtime|null findOneBy(array $criteria, array $orderBy = null)
 * @method Downtime[]    findAll()
 * @method Downtime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DowntimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Downtime::class);
    }


    /**
     * Подготавливает запрос для периода
     *
     * @param DatePeriod $period
     * @return QueryBuilder
     */
    private function getQueryFromPeriod(DatePeriod $period):QueryBuilder
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.drecTimestampKey BETWEEN :start AND :end')
            ->setParameter('start', $period->getStartDate()->format(DATE_ATOM))
            ->setParameter('end', $period->getEndDate()->format(DATE_ATOM))
            ->orderBy('d.drecTimestampKey', 'ASC');
    }

    
    /**
     * @return Downtime[] Returns an array of Downtime objects
     */
    public function findByPeriod( DatePeriod $period)
    {
        return $this->getQueryFromPeriod($period)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Downtime
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
