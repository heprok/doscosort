<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ShiftSheduleRepository;
use Tlc\ManualBundle\Entity\ShiftShedule as BaseShiftShedule;

#[ORM\Entity(repositoryClass: ShiftSheduleRepository::class)]
#[ORM\Table(schema: "ds", name: "shift_shedule", options: ["comment" => "График сменов"])]
#[
    ApiResource(
        collectionOperations: ["get", "post"],
        itemOperations: ["get", "put"],
        normalizationContext: ["groups" => ["shift_shedule:read"]],
        denormalizationContext: ["groups" => ["shift_shedule:write"]]
    )
]
class ShiftShedule extends BaseShiftShedule
{
}
