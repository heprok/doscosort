<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DowntimeGroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Tlc\ManualBundle\Entity\DowntimeGroup as BaseDowntimeGroup;

#[ORM\Entity(repositoryClass: DowntimeGroupRepository::class)]
#[ORM\Table(schema: "ds", name: "downtime_group", options: ["comment" => "Группы причин простоя"])]
#[
    ApiResource(
        collectionOperations: ["get", "post"],
        itemOperations: ["get", "put"],
        normalizationContext: ["groups" => ["downtime_group:read"]],
        denormalizationContext: ["groups" => ["downtime_group:write"]]
    )
]
class DowntimeGroup extends BaseDowntimeGroup
{
    #[ORM\OneToMany(targetEntity: DowntimeCause::class, mappedBy: "groups")]
    protected $downtimeCauses;
}
