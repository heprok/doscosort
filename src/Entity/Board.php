<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BoardRepository;
use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Tlc\ReportBundle\Entity\BaseEntity;

#[ORM\Entity(repositoryClass: BoardRepository::class)]
#[ORM\Table(schema: "ds", name: "board")]
#[ORM\HasLifecycleCallbacks()]

#[ApiResource()]
class Board
{
    private DateTime $drec;

    #[ORM\Id]
    #[ORM\Column(name: "drec", type: "string", options: ["comment" => "Дата записи"])]
    private $drecTimestampKey;


    #[ORM\Column(type: "smallint", options: ["comment" => "Пильная толщина доски, мм."])]
    private $thickness;


    #[ORM\Column(type: "smallint", options: ["comment" => "Пильная ширина доски, мм."])]
    private $width;


    #[ORM\Column(type: "smallint", options: ["comment" => "Пильная длина доски, мм."])]
    private $length;


    #[ORM\ManyToOne(targetEntity: Thickness::class, cascade: ["persist", "refresh"])]
    #[ORM\JoinColumn(referencedColumnName: "nom", name: "nom_thickness", nullable: false, onDelete: "SET NULL")]
    private $nom_thickness;


    #[ORM\ManyToOne(targetEntity: Width::class, cascade: ["persist", "refresh"])]
    #[ORM\JoinColumn(referencedColumnName: "nom", name: "nom_width", nullable: false, onDelete: "SET NULL")]
    private $nom_width;


    #[ORM\ManyToOne(targetEntity: Length::class, cascade: ["persist", "refresh"])]
    #[ORM\JoinColumn(referencedColumnName: "value", name: "nom_length", nullable: false, onDelete: "SET NULL")]
    private $nom_length;


    #[ORM\ManyToOne(targetEntity: Species::class, cascade: ["persist", "refresh"])]
    #[ORM\JoinColumn(nullable: false, onDelete: "SET NULL")]
    private $species;


    #[ORM\ManyToOne(targetEntity: Quality::class)]
    #[ORM\JoinColumn(name: "quality_1", nullable: false)]
    private $quality_1;


    #[ORM\Column(type: "string", length: 16, options: ["comment" => "Название качества 1 операторa в момент записи"])]
    private $quality_1_name;


    #[ORM\ManyToOne(targetEntity: Quality::class)]
    #[ORM\JoinColumn(name: "quality_2", nullable: false)]
    private $quality_2;


    #[ORM\Column(type: "string", length: 16, options: ["comment" => "Название качества 2 операторa в момент записи"])]
    private $quality_2_name;


    #[ORM\Column(type: "smallint", options: ["comment" => "Карман"])]
    private $pocket;

    public function getDrec(): ?\DateTimeInterface
    {
        return $this->drec;
    }

    public function setDrec(\DateTimeInterface $drec): self
    {
        $this->drec = $drec;

        return $this;
    }

    public function getThickness(): ?int
    {
        return $this->thickness;
    }

    public function setThickness(int $thickness): self
    {
        $this->thickness = $thickness;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getNomThickness(): ?Thickness
    {
        return $this->nom_thickness;
    }

    public function setNomThickness(?Thickness $nom_thickness): self
    {
        $this->nom_thickness = $nom_thickness;

        return $this;
    }

    public function getNomWidth(): ?Width
    {
        return $this->nom_width;
    }

    public function setNomWidth(?Width $nom_width): self
    {
        $this->nom_width = $nom_width;

        return $this;
    }

    public function getNomLength(): ?Length
    {
        return $this->nom_length;
    }

    public function setNomLength(?Length $nom_length): self
    {
        $this->nom_length = $nom_length;

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


    public function getQuality1(): ?Quality
    {
        return $this->quality_1;
    }

    public function setQuality1(?Quality $quality_1): self
    {
        $this->quality_1 = $quality_1;

        return $this;
    }

    public function getQuality1Name(): ?string
    {
        return $this->quality_1_name;
    }

    public function setQuality1Name(string $quality_1_name): self
    {
        $this->quality_1_name = $quality_1_name;

        return $this;
    }

    public function getQuality2(): ?Quality
    {
        return $this->quality_2;
    }

    public function setQuality2(?Quality $quality_2): self
    {
        $this->quality_2 = $quality_2;

        return $this;
    }

    public function getQuality2Name(): ?string
    {
        return $this->quality_2_name;
    }

    public function setQuality2Name(string $quality_2_name): self
    {
        $this->quality_2_name = $quality_2_name;

        return $this;
    }

    public function getPocket(): ?int
    {
        return $this->pocket;
    }

    public function setPocket(int $pocket): self
    {
        $this->pocket = $pocket;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function syncDrecTodrecTimestampKey(LifecycleEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $this->drecTimestampKey = $this->drec->format($platform->getDateTimeFormatString());
    }


    #[ORM\PostLoad]
    public function syncdrecTimestampKeyToDrec(LifecycleEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $this->drec = DateTime::createFromFormat($platform->getDateTimeTzFormatString(), $this->drecTimestampKey) ?:
            \DateTime::createFromFormat($platform->getDateTimeFormatString(), $this->drecTimestampKey) ?:
            \DateTime::createFromFormat(BaseEntity::DATE_SECOND_TIMEZONE_FORMAT_DB, $this->drecTimestampKey);
    }
}
