<?php

namespace App\Repository;

use App\Entity\Unload;
use DatePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    private function getBaseQueryFromPeriod(DatePeriod $period, array $sqlWhere = []): QueryBuilder
    {

        $qb = $this->createQueryBuilder('b')
            ->andWhere('b.drecTimestampKey BETWEEN :start AND :end')
            ->setParameter('start', $period->getStartDate()->format(DATE_ATOM))
            ->setParameter('end', $period->getEndDate()->format(DATE_ATOM));

        foreach ($sqlWhere as $where) {
            $query = $where->nameTable . $where->id . ' ' . $where->operator . ' ' . $where->value;
            if ($where->logicalOperator == 'AND')
                $qb->andWhere($query);
            else
                $qb->orWhere($query);
        }

        return $qb;
    }

    /**
     * @return Unload[] Returns an array of Board objects
     */
    public function findByPeriod(DatePeriod $period, array $sqlWhere = []): array
    {
        return $this->getBaseQueryFromPeriod($period, $sqlWhere)
            ->orderBy('b.drecTimestampKey', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getCountUnloadPocketByPeriod(DatePeriod $period, array $sqlWhere = []): int
    {
        $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere);
        return $qb
            ->select('count(1) as countUnloadPocket')
            ->getQuery()
            ->getResult()[0]['countUnloadPocket'] ?? 0;
    }    
    
    public function getAmountUnloadBoradUnloadByPeriod(DatePeriod $period, array $sqlWhere = []): int
    {
        $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere);
        return $qb
            ->select('sum(b.amount) as amountBoard')
            ->getQuery()
            ->getResult()[0]['amountBoard'] ?? 0;
    }    

    public function getVolumeUnloadBoradUnloadByPeriod(DatePeriod $period, array $sqlWhere = []): float
    {
        $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere);
        return $qb
            ->select('sum(b.volume) as volumeBoard')
            ->getQuery()
            ->getResult()[0]['volumeBoard'] ?? 0.0;
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
