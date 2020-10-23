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
    private function getQueryFromPeriod(DatePeriod $period): QueryBuilder
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.drecTimestampKey BETWEEN :start AND :end')
            ->setParameter('start', $period->getStartDate()->format(DATE_ATOM))
            ->setParameter('end', $period->getEndDate()->format(DATE_ATOM))
            ->orderBy('b.drecTimestampKey', 'ASC');
    }


    /**
     * @return Board[] Returns an array of Board objects
     */
    public function findByPeriod(DatePeriod $period)
    {   
        return $this->getQueryFromPeriod($period)
            ->getQuery()
            ->getResult();
    }


    public function findVolumeByPeriod(DatePeriod $period)
    {

        $query = $this->getEntityManager()->createQuery(
            'SELECT 
                s.name,
                b.qualities,
                CONCAT(b.nom_thickness, b.nom_width) AS cut
            FROM
                App\Entity\Board b
            LEFT JOIN b.species s
            GROUP BY
                s.name,
                b.qualities,
                cut,
                b.nom_length'
        );
            dd($query->getResult());
        $query = $this->getEntityManager()->createQuery(
            'SELECT 
                s.name,
                b.qualities,
                b.nom_thickness::varchar || \' × \' || b.nom_width AS cut,
                b.nom_length::real / 1000 AS length,
                count(1),
                sum(nom_length::real / 1000 * nom_width::real / 1000 * nom_length::real / 1000) AS volume_boards
            FROM
                App:Entity:Board b
            LEFT JOIN b.species_id s
            GROUP BY
                s.name,
                b.qualities,
                cut,
                b.nom_length
            ORDER BY
                s.name,
                cut,
                length'
        );
        return $query->getResult();
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
