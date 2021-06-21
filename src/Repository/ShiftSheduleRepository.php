<?php

namespace App\Repository;

use App\Entity\ShiftShedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\ShiftSheduleRepository as BaseShiftSheduleRepository;

/**
 * @method ShiftShedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShiftShedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShiftShedule[]    findAll()
 * @method ShiftShedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiftSheduleRepository extends BaseShiftSheduleRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = ShiftShedule::class;
        parent::__construct($registry);
    }
}
