<?php

namespace App\Repository;

use App\Entity\Downtime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\DowntimeRepository as BaseDowntimeRepository;

/**
 * @method Downtime|null find($id, $lockMode = null, $lockVersion = null)
 * @method Downtime|null findOneBy(array $criteria, array $orderBy = null)
 * @method Downtime[]    findAll()
 * @method Downtime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DowntimeRepository extends BaseDowntimeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = Downtime::class;
        parent::__construct($registry);
    }
}
