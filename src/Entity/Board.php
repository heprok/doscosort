<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BoardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=BoardRepository::class)
 * @ORM\Table(name="ds.board")
 */
class Board
{

    /**
     * @ORM\Id
     * @ORM\Column(type="datetime", 
     *      options={"comment":"Дата записи"})
     */
    private $drec;

    /**
     * @ORM\Column(type="smallint", 
     *      options={"comment":"Пильная толщина доски, мм."})
     */
    private $thickness;

    /**
     * @ORM\Column(type="smallint", 
     *      options={"comment":"Пильная ширина доски, мм."})
     */
    private $width;

    /**
     * @ORM\Column(type="smallint", 
     *      options={"comment":"Пильная длина доски, мм."})
     */
    private $length;

    /**
     * @ORM\ManyToOne(targetEntity=Thickness::class)
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(type="smallint", options={"comment":"Номинальная толщина доски, мм."})
     */
    private $nom_thickness;

    /**
     * @ORM\ManyToOne(targetEntity=Width::class)
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(type="smallint", options={"comment":"Номинальная ширина доски, мм."})
     */
    private $nom_width;

    /**
     * @ORM\ManyToOne(targetEntity=Length::class)
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(type="smallint", options={"comment":"Номинальная длина доски, мм."})
     */
    private $nom_length;

    /**
     * @ORM\ManyToOne(targetEntity=QualityList::class)
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(type="smallint", options={"comment":"ID списка качеств"})
     */
    private $qual_list_id;

    /**
     * @ORM\Column(type="string", length=1, 
     *      options={"comment":"Два качества от операторов, по 4 бита"})
     */
    private $qualities;

    /**
     * @ORM\Column(type="string", length=1, 
     *      options={"comment":"Карман"})
     */
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

    public function getQualListId(): ?QualityList
    {
        return $this->qual_list_id;
    }

    public function setQualListId(?QualityList $qual_list_id): self
    {
        $this->qual_list_id = $qual_list_id;

        return $this;
    }

    public function getQualities(): ?string
    {
        return $this->qualities;
    }

    public function setQualities(string $qualities): self
    {
        $this->qualities = $qualities;

        return $this;
    }

    public function getPocket(): ?string
    {
        return $this->pocket;
    }

    public function setPocket(string $pocket): self
    {
        $this->pocket = $pocket;

        return $this;
    }
}
