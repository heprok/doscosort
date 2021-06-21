<?php

namespace App\Repository;

use App\Entity\People;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\PeopleRepository as BasePeopleRepository;

/**
 * @method People|null find($id, $lockMode = null, $lockVersion = null)
 * @method People|null findOneBy(array $criteria, array $orderBy = null)
 * @method People[]    findAll()
 * @method People[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeopleRepository extends BasePeopleRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = People::class;
        parent::__construct($registry);
    }
}
