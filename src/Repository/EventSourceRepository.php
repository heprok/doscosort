<?php

namespace App\Repository;

use App\Entity\EventSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\EventSourceRepository as BaseEventSourceRepository;

/**
 * @method EventSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventSource[]    findAll()
 * @method EventSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventSourceRepository extends BaseEventSourceRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = EventSource::class;
        parent::__construct($registry);
    }
}
