<?php

namespace App\Repository;

use App\Entity\Duty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\DutyRepository as BaseDutyRepository;

/**
 * @method Duty|null find($id, $lockMode = null, $lockVersion = null)
 * @method Duty|null findOneBy(array $criteria, array $orderBy = null)
 * @method Duty[]    findAll()
 * @method Duty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DutyRepository extends BaseDutyRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = Duty::class;
        parent::__construct($registry);
    }
}
