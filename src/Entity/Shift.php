<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ShiftRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Tlc\ManualBundle\Entity\Shift as BaseShift;

#[ORM\Entity(repositoryClass: ShiftRepository::class)]
#[ORM\Table(schema: "ds", name: "shift", options: ["comment" => "Смены"])]
#[
    ApiResource(
        collectionOperations: ["get"],
        itemOperations: ["get"],
        normalizationContext: ["groups" => ["shift:read"]],
        denormalizationContext: ["groups" => ["shift:write"]]
    )
]
class Shift extends BaseShift
{
    #[ORM\ManyToOne(targetEntity: People::class, cascade: ["persist", "remove", "refresh"])]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    #[Groups(["shift:read"])]
    protected $people;
}
