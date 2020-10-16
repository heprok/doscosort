<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DowntimeCauseRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DowntimeCauseRepository::class)
 * @ORM\Table(name="ds.downtime_cause",
 *      options={"comment":"Причины простоя"})
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put", "delete"},
 *      normalizationContext={"groups"={"downtime_cause:read"}},
 *      denormalizationContext={"groups"={"downtime_cause:write"}}
 * )
 */
class DowntimeCause
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"downtime_cause:read"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=128,
     *      options={"comment":"Название причины"})
     * @Groups({"downtime_cause:read", "downtime_cause:write", "downtime:read"})
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