<?php

namespace App\Entity;

use App\Repository\EventSourceRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EventSourceRepository::class)
 * @ORM\Table(name="ds.event_source",
 *      options={"comment":"Источник события"})
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put", "delete"},
 *      normalizationContext={"groups"={"event_source:read"}},
 *      denormalizationContext={"groups"={"event_source:write"}}
 * )
 */
class EventSource
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=1) 
     * @Groups({"event_source:read"})
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=16, 
     *      options={"comment":"Название события"})
     * @Groups({"event_source:read", "event:read"})
     */
    private string $name;

    public function __construct(string $id, string $name)
    {   
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->getName() ?? '';
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
