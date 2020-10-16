<?php

namespace App\Repository;

use App\Entity\Event;
use DatePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
    private function getQueryFromPeriod(DatePeriod $period)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.drecTimestampKey BETWEEN :start AND :end')
            ->setParameter('start', $period->getStartDate()->format(DATE_ATOM))
            ->setParameter('end', $period->getEndDate()->format(DATE_ATOM))
            ->orderBy('e.drecTimestampKey', 'ASC');

    }
    /**
     * Возращает события с заданным типом и источником
     *
     * @param string[] $type
     * @param string[] $source
     * @return Event[]
     */
    public function findByTypeAndSourceFromPeriod(DatePeriod $period, array $type, array $source):array
    {
        $type = implode(',', $type);
        $source = implode(',', $source);
        return $this->getQueryFromPeriod($period)
            ->andWhere('e.type IN( :type )')
            ->andWhere('e.source IN( :source )')
            ->setParameter('type', $type)
            ->setParameter('source', $source)
            ->getQuery()
            ->getResult()
        ;
    }
}
