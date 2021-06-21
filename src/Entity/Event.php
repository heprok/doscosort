<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Tlc\ManualBundle\Entity\Event as BaseEvent;
use Tlc\ManualBundle\Filter\DateFilter;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(schema: "ds", name: "event")]
#[
    ApiResource(
        collectionOperations: ["get"],
        itemOperations: ["get"],
        normalizationContext: ["groups" => ["event:read"]],
        denormalizationContext: ["groups" => ["event:write"]]
    )
]
#[ApiFilter(DateFilter::class, properties: ["drecTimestampKey"])]
#[ApiFilter(SearchFilter::class, properties: ["type.id" => "partial", "source.id" => "partial"])]
#[ORM\HasLifecycleCallbacks]
class Event extends BaseEvent
{
    #[ORM\ManyToOne(targetEntity: EventType::class)]
    #[ORM\JoinColumn(nullable: false, name: "type")]
    #[Groups(["event:read"])]
    protected $type;

    #[ORM\ManyToOne(targetEntity: EventSource::class)]
    #[ORM\JoinColumn(nullable: false, name: "source")]
    #[Groups(["event:read"])]
    protected $source;
}