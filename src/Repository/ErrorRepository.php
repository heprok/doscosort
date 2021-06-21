<?php

namespace App\Repository;

use App\Entity\Error;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\ErrorRepository as BaseErrorRepository;

/**
 * @method Error|null find($id, $lockMode = null, $lockVersion = null)
 * @method Error|null findOneBy(array $criteria, array $orderBy = null)
 * @method Error[]    findAll()
 * @method Error[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ErrorRepository extends BaseErrorRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = Error::class;
        parent::__construct($registry);
    }
}
