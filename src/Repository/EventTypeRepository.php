<?php

namespace App\Repository;

use App\Entity\EventType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\EventTypeRepository as BaseEventTypeRepository;

/**
 * @method EventType|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventType|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventType[]    findAll()
 * @method EventType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventTypeRepository extends BaseEventTypeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = EventType::class;
        parent::__construct($registry);
    }
}
