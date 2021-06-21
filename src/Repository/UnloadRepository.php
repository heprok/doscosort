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

        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.drecTimestampKey BETWEEN :start AND :end')
            ->setParameter('start', $period->getStartDate()->format(DATE_ATOM))
            ->setParameter('end', $period->end ? $period->getEndDate()->format(DATE_ATOM) : date(DATE_ATOM));

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
            ->addSelect('standard_length(g.max_length)')
            ->orderBy('u.drecTimestampKey', 'ASC')
            ->leftJoin('u.group', 'g')
            ->getQuery()
            ->getResult();
    }

    public function findGroupByCutQualitiesByPeriod(DatePeriod $period): array
    {
        return $this->getBaseQueryFromPeriod($period)
            ->select(
                'concat_ws(\' \', u.qualities, concat_ws(\' × \', g.thickness, g.width)) as id',
                'u.qualities',
                'concat_ws(\' × \', g.thickness, g.width) AS cut',
                'count(1) as unload_pocket',
                'sum(u.volume) as total_volume',
                'sum(u.amount) as total_amount'
            )
            ->leftJoin('u.group', 'g')
            ->addGroupBy('u.qualities, cut')
            ->getQuery()
            ->getResult();
    }

    public function findLastUnload(DatePeriod $period, array $sqlWhere = [])
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.drecTimestampKey', 'DESC')
            ->andWhere('u.drecTimestampKey <= :end')
            ->setParameter('end', $period->getEndDate()->format(DATE_ATOM))
            ->leftJoin('u.group', 'g');


        foreach ($sqlWhere as $where) {
            $query = $where->nameTable . $where->id . ' ' . $where->operator . ' ' . $where->value;
            if ($where->logicalOperator == 'AND')
                $qb->andWhere($query);
            else
                $qb->orWhere($query);
        }

        return $qb->getQuery()
            // ->getSQL();
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    /**
     * @return Unload[] Returns an array of Unload objects
     */
    public function findByCutQuality(DatePeriod $period, string $cut, string $qualities): array
    {
        $cut = explode(' × ', $cut);
        return $this->getBaseQueryFromPeriod($period)

            ->andWhere('u.qualities = :qualities')
            ->andWhere('g.thickness = :thickness')
            ->andWhere('g.width = :width')
            ->setParameter('qualities', $qualities)
            ->setParameter('width', $cut[1])
            ->setParameter('thickness', $cut[0])
            ->leftJoin('u.group', 'g')
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

    public function getAmountUnloadBoardUnloadByPeriod(DatePeriod $period, array $sqlWhere = []): int
    {
        $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere);
        return $qb
            ->select('sum(u.amount) as amountBoard')
            ->getQuery()
            ->getResult()[0]['amountBoard'] ?? 0;
    }

    public function getVolumeUnloadBoardUnloadByPeriod(DatePeriod $period, array $sqlWhere = []): float
    {
        $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere);
        return $qb
            ->select('sum(u.volume) as volumeBoard')
            ->getQuery()
            ->getResult()[0]['volumeBoard'] ?? 0.0;
    }



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
