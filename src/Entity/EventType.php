<?php

namespace App\Entity;

use App\Repository\EventTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EventTypeRepository::class)
 * @ORM\Table(name="ds.event_type",
 *      options={"comment":"Типы события"})
 */
#[
ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: ["get", "put", "delete"],
    normalizationContext: ["groups" => ["event_type:read"]],
    denormalizationContext: ["groups" => ["event_type:write"]]
)]
class EventType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=1)
     */
    #[Groups(["event_type:read"])]
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     */
    #[Groups(["event_type:read", "event:read"])]
    private $name;

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
        return $this->name ?? '';
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
