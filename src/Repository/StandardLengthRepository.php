<?php

namespace App\Repository;

use App\Entity\StandardLength;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\StandardLengthRepository as BaseStandardLengthRepository;

/**
 * @method StandardLength|null find($id, $lockMode = null, $lockVersion = null)
 * @method StandardLength|null findOneBy(array $criteria, array $orderBy = null)
 * @method StandardLength[]    findAll()
 * @method StandardLength[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StandardLengthRepository extends BaseStandardLengthRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = StandardLength::class;
        parent::__construct($registry);
    }
}
