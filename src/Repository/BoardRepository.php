<?php

namespace App\Repository;

use App\Entity\Board;
use DatePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Board|null find($id, $lockMode = null, $lockVersion = null)
 * @method Board|null findOneBy(array $criteria, array $orderBy = null)
 * @method Board[]    findAll()
 * @method Board[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Board::class);
    }

    /**
     * Подготавливает запрос для периода
     *
     * @param DatePeriod $period
     * @return QueryBuilder
     */
    private function getBaseQueryFromPeriod(DatePeriod $period, array $sqlWhere = []): QueryBuilder
    {
        $qb = $this->createQueryBuilder('b')
            ->andWhere('b.drecTimestampKey BETWEEN :start AND :end')
            ->setParameter('start', $period->getStartDate()->format(DATE_RFC3339_EXTENDED))
            ->setParameter('end', $period->getEndDate()->format(DATE_RFC3339_EXTENDED))
            ->leftJoin('b.species', 's');
            // ->leftJoin('b.nom_thickness', 't')
            // ->leftJoin('b.nom_width', 'w')
            // ->leftJoin('b.nom_length', 'l');


        foreach ($sqlWhere as $where) {
            $query = $where->nameTable . $where->id . ' ' . $where->operator . ' ' . $where->value;
            if ($where->logicalOperator == 'AND')
                $qb->andWhere($query);
            else
                $qb->orWhere($query);
        }

        return $qb;

        // ->orderBy('b.drec', 'ASC');
    }

    /**
     * @return Board[] Returns an array of Board objects
     */
    public function findByPeriod(DatePeriod $period, array $sqlWhere = [])
    {   
        return $this->getBaseQueryFromPeriod($period, $sqlWhere)
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult();
    }

    public function getReportVolumeBoardByPeriod(DatePeriod $period, array $sqlWhere = [])
    {
        $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere);
        return $qb
            ->select(
                's.name as name_species',
                // 'standard_length (b.length) AS st_length',
                'b.quality_1_name',
                'standard_length(b.nom_length) as length',
                'concat_ws(\' × \', b.nom_thickness, b.nom_width) AS cut',
                'count(1) AS count_board',
                'sum(CAST(standard_length(b.nom_length) as real) / 1000 * CAST(b.nom_width as real) / 1000 * CAST(b.nom_thickness as real) / 1000) AS volume_boards'
                // 'volume_boards (unnest(b.boards), b.length) AS volume_boards'
            )
            ->addGroupBy('name_species', 'cut', 'b.quality_1_name', 'length')
            ->addOrderBy('name_species, cut, b.quality_1_name, length')
            ->getQuery()
            ->getResult();
    }


    public function getVolumeBoardsByPeriod(DatePeriod $period, array $sqlWhere = []):float
    {
        $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere);

        return $qb
                ->select('sum(CAST(standard_length(b.nom_length) as real) / 1000 * CAST(b.nom_width as real) / 1000 * CAST(b.nom_thickness as real) / 1000) AS volume_boards')
                ->getQuery()
                ->getResult()[0]['volume_boards'] ?? 0.0;
    }

    public function getCountBoardsByPeriod(DatePeriod $period, array $sqlWhere = []):int
    {
        $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere);

        return $qb
                ->select('count(1) AS count_boards')
                ->getQuery()
                ->getResult()[0]['count_boards'] ?? 0;
    }

    // /**
    //  * @return Board[] Returns an array of Board objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Board
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
