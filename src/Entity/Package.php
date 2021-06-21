<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PackageRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\Types\Leam;
use Symfony\Component\Serializer\Annotation\Groups;
use Tlc\ReportBundle\Entity\BaseEntity;

#[ORM\Entity(repositoryClass: PackageRepository::class)]
#[ORM\Table(schema: "ds", name: "package", options: ["comment" => "Сформированные пакеты"])]
#[
    ApiResource(
        collectionOperations: ["get"],
        itemOperations: ["get", 'put'],
        normalizationContext: ["groups" => ["package:read"]],
        denormalizationContext: ["groups" => ["package:write"], 'disable_type_enforcement' => true]
    )
]
class Package
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["package:read"])]
    private int $id;


    #[ORM\Column(type: "datetimetz", options: ["comment" => "Время формирования пакета"])]
    #[Groups(["package:read"])]
    private DateTime $drec;


    #[ORM\ManyToOne(targetEntity: Species::class)]
    #[Groups(["package:read", "package:write"])]
    private ?Species $species;


    #[ORM\Column(type: "smallint", nullable: true, options: ["comment" => "Толщина"])]

    #[Groups(["package:read", "package:write"])]
    private ?int $thickness;


    #[ORM\Column(type: "smallint", nullable: true, options: ["comment" => "Ширина"])]
    #[Groups(["package:read", "package:write"])]
    private ?int $width;


    #[ORM\Column(type: "string", length: 32, nullable: true, options: ["comment" => "Качество"])]
    #[Groups(["package:read", "package:write"])]
    private ?string $qualities;


    #[ORM\Column(type: "leam[]", options: ["comment" => "Массив досок"])]
    #[Groups(["package:read", "package:write"])]
    private $boards = [];


    #[ORM\Column(type: "boolean", options: ["comment" => "Сухая или сырая"])]
    #[Groups(["package:read", "package:write"])]
    private bool $dry;


    #[ORM\OneToMany(targetEntity: PackageMove::class, mappedBy: "package")]
    #[Groups(["package:read", "package:write"])]
    private $packageMoves;

    public function __construct()
    {
        $this->packageMoves = new ArrayCollection();
    }


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

    #[Groups(["package:read"])]
    public function getDrecTime(): ?string
    {
        return $this->drec->format(BaseEntity::DATETIME_FOR_FRONT);
    }

    #[Groups(["package:read"])]
    public function getCut(): string
    {
        return $this->thickness . ' × ' . $this->width;
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

    #[Groups(["package:read"])]
    public function getRangeLengths(): string
    {
        $min = $max = $this->boards[0]->length;
        foreach ($this->boards as $board) {
            $max = $board->length >= $max ? $board->length : $max;
            $min = $board->length < $min ? $board->length : $min;
        }
        return (($max = number_format($max / 1000, 1) . 'м') == ($min = number_format($min / 1000, 1) . 'м')) ? $max  : "$min - $max";
    }

    #[Groups(["package:read"])]
    public function getCount(): int
    {
        $result = 0;
        foreach ($this->boards as $board) {
            $result += $board->amount;
        }

        return $result;
    }
    #[Groups(["package:read"])]
    public function getCurrentLocation()
    {
        return $this->packageMoves->last() ? $this->packageMoves->last()?->getDst() : null;
    }
    #[Groups(["package:read"])]
    public function getVolume(): ?float
    {
        if ($this->thickness && $this->width && $this->species && $this->qualities) {

            $result = 0.0;

            foreach ($this->boards as $board) {
                $result += $board->length * $this->thickness * $this->width * $board->amount / 1e9;
            }

            return round($result, BaseEntity::PRECISION_FOR_FLOAT);
        } else {
            return null;
        }
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

    #[Groups(["package:read"])]
    public function getBoardsArray(): array
    {
        $result = [];

        foreach ($this->boards as $key => $board) {
            if ($board instanceof Leam) {
                $result[$key]['length'] =  $board->length;
                $result[$key]['amount'] =  $board->amount;
            }
        }
        return $result;
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

    /**
     * @return Collection|PackageMove[]
     */
    public function getPackageMoves(): Collection
    {
        return $this->packageMoves;
    }

    public function addPackageMove(PackageMove $packageMove): self
    {
        if (!$this->packageMoves->contains($packageMove)) {
            $this->packageMoves[] = $packageMove;
            $packageMove->setPackage($this);
        }

        return $this;
    }

    public function removePackageMove(PackageMove $packageMove): self
    {
        if ($this->packageMoves->removeElement($packageMove)) {
            // set the owning side to null (unless already changed)
            if ($packageMove->getPackage() === $this) {
                $packageMove->setPackage(null);
            }
        }

        return $this;
    }
}
