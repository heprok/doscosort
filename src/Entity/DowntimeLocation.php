<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DowntimeLocationRepository;
use Doctrine\ORM\Mapping as ORM;
use Tlc\ManualBundle\Entity\DowntimeLocation as BaseDowntimeLocation;

#[ORM\Entity(repositoryClass: DowntimeLocationRepository::class)]
#[ORM\Table(schema: "ds", name: "downtime_location", options: ["comment" => "Локации простоя"])]
#[
    ApiResource(
        collectionOperations: ["get", "post"],
        itemOperations: ["get", "put"],
        normalizationContext: ["groups" => ["downtime_location:read"]],
        denormalizationContext: ["groups" => ["downtime_location:write"]]
    )
]
class DowntimeLocation extends BaseDowntimeLocation
{
    #[ORM\OneToMany(targetEntity: DowntimePlace::class, mappedBy: "location")]
    protected $downtimePlaces;
}
