<?php

namespace App\Repository;

use App\Entity\DowntimeCause;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\DowntimeCauseRepository as BaseDowntimeCauseRepository;

/**
 * @method DowntimeCause|null find($id, $lockMode = null, $lockVersion = null)
 * @method DowntimeCause|null findOneBy(array $criteria, array $orderBy = null)
 * @method DowntimeCause[]    findAll()
 * @method DowntimeCause[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DowntimeCauseRepository extends BaseDowntimeCauseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = DowntimeCause::class;
        parent::__construct($registry);
    }
}
