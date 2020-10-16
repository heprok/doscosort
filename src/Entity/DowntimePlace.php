<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DowntimePlaceRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DowntimePlaceRepository::class)
 * @ORM\Table(name="ds.downtime_place",
 *      options={"comment":"Места простоя"})
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put", "delete"},
 *      normalizationContext={"groups"={"downtime_place:read"}},
 *      denormalizationContext={"groups"={"downtime_place:write"}}
 * )
 */
class DowntimePlace
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"downtime_place:read"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=128,
     *      options={"comment":"Название места"})
     * @Groups({"downtime_place:read", "downtime_place:write", "downtime:read"})
     */
    private string $name;

    public function __construct(string $name)
    {
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