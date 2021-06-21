<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ErrorRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Tlc\ManualBundle\Entity\Error as BaseError;

#[ORM\Entity(repositoryClass: ErrorRepository::class)]
#[ORM\Table(schema: "ds", name: "error", options: ["comment" => "Ошибки"])]
#[
    ApiResource(
        collectionOperations: ["get", "post"],
        itemOperations: ["get", "put"],
        normalizationContext: ["groups" => ["error:read"]],
        denormalizationContext: ["groups" => ["error:write"]]
    )
]
class Error extends BaseError
{
}
