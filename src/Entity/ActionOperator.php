<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ActionOperatorRepository;
use Tlc\ManualBundle\Entity\ActionOperator as BaseActionOperator;

#[ORM\Entity(repositoryClass: ActionOperatorRepository::class)]
#[ORM\Table(schema: "ds", name: "action_operator", options: ["comment" => "Действия оператора"])]
#[
    ApiResource(
        collectionOperations: ["get", "post"],
        itemOperations: ["get", "put", "delete"],
        normalizationContext: ["groups" => ["action_operator:read"]],
        denormalizationContext: ["groups" => ["action_operator:write"], "disable_type_enforcement" => true]
    )
]
class ActionOperator extends BaseActionOperator
{
}
