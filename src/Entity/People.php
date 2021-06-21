<?php

namespace App\Entity;

use App\Repository\PeopleRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Tlc\ManualBundle\Entity\People as BasePeople;

#[ORM\Entity(repositoryClass: PeopleRepository::class)]
#[ORM\Table(schema: "ds", name: "people", options: ["comment" => "Люди"])]
#[
    ApiResource(
        collectionOperations: ["get", "post"],
        itemOperations: ["get", "put", "delete"],
        normalizationContext: ["groups" => ["people:read"]],
        denormalizationContext: ["groups" => ["people:write"]]
    )
]
class People extends BasePeople
{
    #[ORM\ManyToMany(targetEntity: Duty::class)]
    #[ORM\JoinTable(schema: "ds", name: "people_to_duty")]
    #[Groups(["people:write", "people:read"])]
    protected $duty;
}
