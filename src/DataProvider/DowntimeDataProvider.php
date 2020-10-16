<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Downtime;
use Doctrine\Persistence\ManagerRegistry;

final class DowntimeDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(string $resourceClass, $identifiers, string $operationName = null, array $context = [])
    {

        // dd($resourceClass, $identifiers, $operationName, $context);
        $repository = $this->managerRegistry->getRepository($resourceClass);
        return $repository->find($identifiers);
        // dd(1);
        // Our identifier is:
        // $identifiers['code']
        // although it's a string, it's not an instance of Uuid and we wanted to retrieve the timestamp of our time-based uuid:
        // $identifiers['code']->getTimestamp()
        // dd($identifiers);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Downtime::class;
    }
}