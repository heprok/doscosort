<?php

namespace App\Repository;

use App\Entity\DowntimeLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\DowntimeLocationRepository as BaseDowntimeLocationRepository;

/**
 * @method DowntimeLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DowntimeLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DowntimeLocation[]    findAll()
 * @method DowntimeLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DowntimeLocationRepository extends BaseDowntimeLocationRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = DowntimeLocation::class;
        parent::__construct($registry);
    }
}
