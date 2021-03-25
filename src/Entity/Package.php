<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PackageRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PackageRepository::class)
 * @ORM\Table(name="ds.package",
 *      options={"comment":"Сформированные пакеты"})
 */
#[ApiResource(
    collectionOperations: ["get"],
    itemOperations: ["get", 'put'],
    normalizationContext: ["groups" => ["package:read"]],
    denormalizationContext: ["groups" => ["package:write"]]
)]
// #[ApiFilter(DateFilter::class, properties: ["drecTimestampKey"])]
class Package
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetimetz",
     *      options={"comment":"Время формирования пакета"})
     */
    #[Groups(["package:read"])]
    private DateTime $drec;

    /**
     * @ORM\ManyToOne(targetEntity=Species::class)
     */
    #[Groups(["package:read", "package:write"])]
    private Species $species;

    /**
     * @ORM\Column(type="smallint", nullable=true,
     *      options={"comment":"Толщина"})
     */
    #[Groups(["package:read", "package:write"])]
    private int $thickness;

    /**
     * @ORM\Column(type="smallint", nullable=true,
     *      options={"comment":"Ширина"})
     */
    #[Groups(["package:read", "package:write"])]
    private int $width;

    /**
     * @ORM\Column(type="string", length=32, nullable=true,
     *      options={"comment":"Качество"})
     */
    #[Groups(["package:read", "package:write"])]
    private string $qualities;

    /**
     * @ORM\Column(type="leam[]",
     *      options={"comment":"Массив досок"})
     */
    #[Groups(["package:read"])]
    private $boards = [];

    /**
     * @ORM\Column(type="boolean",
     *      options={"comment":"Сухая или сырая"})
     */
    #[Groups(["package:read"])]
    private bool $dry;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDrec(): ?\DateTimeInterface
    {
        return $this->drec;
    }

    public function setDrec(\DateTimeInterface $drec): self
    {
        $this->drec = $drec;

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function setSpecies(?Species $species): self
    {
        $this->species = $species;

        return $this;
    }

    public function getThickness(): ?int
    {
        return $this->thickness;
    }

    public function setThickness(?int $thickness): self
    {
        $this->thickness = $thickness;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getQualities(): ?string
    {
        return $this->qualities;
    }

    public function setQualities(?string $qualities): self
    {
        $this->qualities = $qualities;

        return $this;
    }

    public function getBoards(): ?array
    {
        return $this->boards;
    }

    public function setBoards(array $boards): self
    {
        $this->boards = $boards;

        return $this;
    }

    public function getDry(): ?bool
    {
        return $this->dry;
    }

    public function setDry(bool $dry): self
    {
        $this->dry = $dry;

        return $this;
    }
}
