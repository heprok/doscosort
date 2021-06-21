<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DowntimeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Tlc\ManualBundle\Entity\Downtime as BaseDowntime;

#[ORM\Entity(repositoryClass: DowntimeRepository::class)]
#[ORM\Table(schema: "ds", name: "downtime", options: ["comment" => "Простои"])]
#[
    ApiResource(
        collectionOperations: ["get"],
        itemOperations: ["get"],
        normalizationContext: ["groups" => ["downtime:read"]],
        denormalizationContext: ["groups" => ["downtime:write"]]
    )
]
class Downtime extends BaseDowntime
{
    #[ORM\ManyToOne(targetEntity: DowntimeCause::class, cascade: ["persist", "refresh"])]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    #[Groups(["downtime:read"])]
    protected $cause;

    #[ORM\ManyToOne(targetEntity: DowntimePlace::class, cascade: ["persist", "refresh"])]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    #[Groups(["downtime:read"])]
    protected $place;
}
