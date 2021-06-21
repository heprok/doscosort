<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DowntimePlaceRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Tlc\ManualBundle\Entity\DowntimePlace as BaseDowntimePlace;

#[ORM\Entity(repositoryClass: DowntimePlaceRepository::class)]
#[ORM\Table(schema: "ds", name: "downtime_place", options: ["comment" => "Места простоя"])]
#[
    ApiResource(
        collectionOperations: ["get", "post"],
        itemOperations: ["get", "put"],
        normalizationContext: ["groups" => ["downtime_place:read"]],
        denormalizationContext: ["groups" => ["downtime_place:write"]]
    )
]
class DowntimePlace extends BaseDowntimePlace
{

    #[ORM\ManyToOne(targetEntity: DowntimeLocation::class, inversedBy: "downtimePlaces")]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["downtime_place:read", "downtime_place:write"])]
    protected $location;

    #[ORM\OneToMany(targetEntity: BreakShedule::class, mappedBy: "place")]
    protected $breakShedules;
}
