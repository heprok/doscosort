<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DowntimeCauseRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Tlc\ManualBundle\Entity\DowntimeCause as BaseDowntimeCause;

#[ORM\Entity(repositoryClass: DowntimeCauseRepository::class)]
#[ORM\Table(schema: "ds", name: "downtime_cause", options: ["comment" => "Причины простоя"])]
#[
    ApiResource(
        collectionOperations: ["get", "post"],
        itemOperations: ["get", "put"],
        normalizationContext: ["groups" => ["downtime_cause:read"]],
        denormalizationContext: ["groups" => ["downtime_cause:write"]]
    )
]
class DowntimeCause extends BaseDowntimeCause
{
    #[ORM\OneToMany(targetEntity: BreakShedule::class, mappedBy: "cause")]
    protected $breakShedules;

    #[ORM\ManyToOne(targetEntity: DowntimeGroup::class, inversedBy: "downtimeCauses")]
    #[ORM\JoinColumn(nullable: false, name: "group_id")]
    #[Groups(["downtime_cause:read", "downtime_cause:write"])]
    protected $groups;
}
