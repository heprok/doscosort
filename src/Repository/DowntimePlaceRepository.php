<?php

namespace App\Repository;

use App\Entity\DowntimePlace;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\DowntimePlaceRepository as BaseDowntimePlaceRepository;

/**
 * @method DowntimePlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method DowntimePlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method DowntimePlace[]    findAll()
 * @method DowntimePlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DowntimePlaceRepository extends BaseDowntimePlaceRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = DowntimePlace::class;
        parent::__construct($registry);
    }
}
