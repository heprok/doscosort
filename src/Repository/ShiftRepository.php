<?php

namespace App\Repository;

use App\Entity\Shift;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\ShiftRepository as BaseShiftRepository;

/**
 * @method Shift|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shift|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shift[]    findAll()
 * @method Shift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiftRepository extends BaseShiftRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = Shift::class;
        parent::__construct($registry);
    }
}
