<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\StandardLengthRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Tlc\ManualBundle\Entity\StandardLength as BaseStandardLength;

#[ORM\Entity(repositoryClass: StandardLengthRepository::class)]
#[ORM\Table(schema: "ds",name: "standard_length", options: ["comment" => "Cтандартные длины"])]
#[ApiResource(
    collectionOperations: [
        'get' => ['method' => 'GET', 'path' => '/lengths'],
        'post' => ['method' => 'POST', 'path' => '/lengths']
    ],
    itemOperations: [
        'get' => ['method' => 'GET', 'path' => '/lengths/{standard}'],
        'put' => ['method' => 'PUT', 'path' => '/lengths/{standard}'],
        'delete' => ['method' => 'DELETE', 'path' => '/lengths/{standard}'],
    ],
    normalizationContext: [
        "groups" => ['standard_length:read']
    ],
    denormalizationContext: [
        'groups' => ['standard_length:write'],
        'disable_type_enforcement' => true
    ],
)]
class StandardLength extends BaseStandardLength 
{
}