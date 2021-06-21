<?php

namespace App\Entity;

use App\Repository\BreakSheduleRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Tlc\ManualBundle\Entity\BreakShedule as BaseBreakShedule;

#[ORM\Entity(repositoryClass: BreakSheduleRepository::class)]
#[ORM\Table(schema: "ds", name: "break_shedule", options: ["comment" => "График перерывов"])]
#[ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: ["get", "put"],
    normalizationContext: ["groups" => ["break_shedule:read"]],
    denormalizationContext: ["groups" => ["break_shedule:write"]]
)]
class BreakShedule extends BaseBreakShedule
{
    #[ORM\ManyToOne(targetEntity: DowntimePlace::class, cascade: ["persist", "refresh"], inversedBy: "breakShedules")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    #[Groups(["break_shedule:read", "break_shedule:write", "downtime_place:read"])]
    protected DowntimePlace $place;


    #[ORM\ManyToOne(targetEntity: DowntimeCause::class, cascade: ["persist", "refresh"], inversedBy: "breakShedules")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    #[Groups(["break_shedule:read", "break_shedule:write", "downtime_place:read"])]
    protected DowntimeCause $cause;
}
