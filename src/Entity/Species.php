<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SpeciesRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Tlc\ManualBundle\Entity\Species as BaseSpecies;

#[
    ApiResource(
        collectionOperations: ["get"],
        itemOperations: ["get", "put"],
        normalizationContext: ["groups" => ["species:read"]],
        denormalizationContext: ["groups" => ["species:write"]]
    )
]
#[ORM\Table(name: 'species', schema: 'dic', options: ['comment' => 'Породы древесины'])]
#[ORM\Entity(repositoryClass: SpeciesRepository::class)]
class Species extends BaseSpecies
{
}
