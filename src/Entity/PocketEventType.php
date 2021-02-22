<?php

namespace App\Entity;

use App\Repository\PocketEventTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PocketEventTypeRepository::class)
 * @ORM\Table(name="ds.pocket_event_type",
 *      options={"comment":"Типи события для карманов"})
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put", "delete"},
 *      normalizationContext={"groups"={"pocket_event_type:read"}},
 *      denormalizationContext={"groups"={"pocket_event_type:write"}}
 * )
 */
class PocketEventType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=1)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32,
     *      options={"comment":"Название типа"})
     * @Groups({"pocket_event_type:read", "event:read"})
     */
    private $name;

    public function __construct(string $id, string $name)
    {   
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
