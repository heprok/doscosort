<?php

namespace App\Repository;

use App\Entity\BreakShedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\BreakSheduleRepository as BaseBreakSheduleRepository;

/**
 * @method BreakShedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method BreakShedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method BreakShedule[]    findAll()
 * @method BreakShedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BreakSheduleRepository extends BaseBreakSheduleRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = BreakShedule::class;
        parent::__construct($registry);
    }
}
