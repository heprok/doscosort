<?php

namespace App\Repository;

use App\Entity\ActionOperator;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\ActionOperatorRepository as BaseActionOperatorRepository;

/**
 * @method ActionOperator|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionOperator|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionOperator[]    findAll()
 * @method ActionOperator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionOperatorRepository extends BaseActionOperatorRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = ActionOperator::class;
        parent::__construct($registry);
    }
}
