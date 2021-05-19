<?php

namespace App\Repository;

use App\Entity\Shift;
use DateInterval;
use DatePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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
     *  где Where если смена не завершена, то берём половину времени прошедшего от начала смены до текущего моента 
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

    public function getTimeWorkForOperator(DatePeriod $period, int $idOperator, string $format = 'hours')
    {
        return $this->getQueryFromPeriod($period)
            ->select("date_part('$format', sum(age(s.stop, s.startTimestampKey))) as timework")
            ->andWhere('s.people = :idOperator')
            ->setParameter('idOperator', $idOperator)
            ->orderBy('timework')
            ->getQuery()
            ->getResult()[0]['timework'] ?? 0;

    }

    public function getTimeDowntimeForOperator(DatePeriod $period, int $idOperator, string $formatInterval = '%H:%I:%S'): string
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('inter', 'inter');
        $sql = "SELECT sum((
            SELECT
                sum(AGE(d.finish, d.drec))
            FROM ds.downtime d
        WHERE
            d.drec BETWEEN s.start AND s.stop
            AND (d.cause_id NOT IN ( SELECT DISTINCT
                        b.cause_id FROM ds.break_shedule b)
                OR d.cause_id IS NULL))) inter
                FROM ds.shift s
        WHERE (CAST(((COALESCE(s.stop, NOW()) - s.start) / 2 + s.start) AS timestamp) 
        BETWEEN :start AND :end) AND s.people_id = :people_id";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        $query->setParameter('start', $period->getStartDate()->format(DATE_ATOM));
        $query->setParameter('end', $period->getEndDate()->format(DATE_ATOM));
        $query->setParameter('people_id', $idOperator);
        $durationDowntime = $query->getResult()[0]['inter'];

        list($hours, $minutes, $seconds, $milisecond) = sscanf($durationDowntime, '%d:%d:%d.%d');
        $interval = new DateInterval(sprintf('PT%dH%dM%dS', $hours, $minutes, $seconds));
        
        return $interval->format($formatInterval);
    }
    public function getCountShiftForOperator(DatePeriod $period, int $idOperator):int 
    {
        return $this->getQueryFromPeriod($period)
            ->select('count(1) as count')
            ->andWhere('s.people = :idOperator')
            ->setParameter('idOperator', $idOperator)
            ->orderBy('count')
            ->getQuery()
            // ->getOneOrNullResult();
            // ->getSQL();
            ->getResult()[0]['count'] ?? 0;
            // dd($dd);

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

    public function getPeopleForByPeriod(DatePeriod $period)
    {
        return $this->getQueryFromPeriod($period)
            ->select('p')
            ->leftJoin('App:People', 'p', \Doctrine\ORM\Query\Expr\Join::WITH, 's.people = p.id')
            ->groupBy('p')
            ->orderBy('p.fam')
            ->getQuery()
            ->getResult();
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
