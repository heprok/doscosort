<?php

namespace App\Repository;

use App\Entity\Shift;
use DateInterval;
use DatePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Shift|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shift|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shift[]    findAll()
 * @method Shift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shift::class);
    }

    /**
     * Подготавливает запрос для периода
     *
     * @param DatePeriod $period
     * @return QueryBuilder
     */
    private function getQueryFromPeriod(DatePeriod $period): QueryBuilder
    {
        
        return $this->createQueryBuilder('s')
            ->where('CAST(((COALESCE(s.stop, now()) - s.startTimestampKey) / 2 + s.startTimestampKey) as timestamp) BETWEEN :start AND :end')
            ->setParameter('start', $period->getStartDate()->format(DATE_ATOM))
            ->setParameter('end', $period->getEndDate()->format(DATE_ATOM))
            ->orderBy('s.startTimestampKey', 'ASC');
    }

    /**
     * @return Shift
     */
    public function getLastShift()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.startTimestampKey', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Shift[] Returns an array of Shift objects
     */
    public function findByPeriod(DatePeriod $period)
    {
        return $this->getQueryFromPeriod($period)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Shift
     */
    public function getCurrentShift()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.stop is null')
            ->orderBy('s.startTimestampKey', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }
    // /**
    //  * @return Shift[] Returns an array of Shift objects
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
    public function findOneBySomeField($value): ?Shift
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
