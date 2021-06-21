<?php

namespace App\Repository;

use App\Entity\DowntimeGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\DowntimeGroupRepository as BaseDowntimeGroupRepository;

/**
 * @method DowntimeGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method DowntimeGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method DowntimeGroup[]    findAll()
 * @method DowntimeGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DowntimeGroupRepository extends BaseDowntimeGroupRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = DowntimeGroup::class;
        parent::__construct($registry);
    }
}
