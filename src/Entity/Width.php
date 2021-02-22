<?php

namespace App\Entity;

use App\Repository\WidthRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=WidthRepository::class)
 * @ORM\Table(name="ds.width", 
 *      options={"comment":"Справочник ширин"})
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put", "delete"},
 *      normalizationContext={"groups"={"width:read"}},
 *      denormalizationContext={"groups"={"width:write"}, "disable_type_enforcement"=true}
 * )
 */
class Width
{
    /**
     * @ORM\Id
     * @ORM\Column(type="smallint", 
     *      options={"comment":"Номинальная ширина"})
     * @Groups({"width:read", "width:write"})
     */
    private int $nom;

    /**
     * @ORM\Column(type="smallint", 
     *      options={"comment":"Минимальная ширина"})
     * @Groups({"width:read", "width:write"})
     */
    private int $min;

    /**
     * @ORM\Column(type="smallint", 
     *      options={"comment":"Максимальная ширина", "check":"min <= max"})
     * @Groups({"width:read", "width:write"})
     */
    private int $max;

    public function __toString()
    {
        return strval($this->nom);
    }

    public function __construct(int $nom, int $min, int $max)
    {
        $this->nom = $nom;
        $this->min = $min;
        $this->max = $max;
    }

    public function getNom(): int
    {
        return $this->nom;
    }

    public function getMin(): int
    {
        return $this->min;
    }

    public function setMin(int $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function setMax(int $max): self
    {
        $this->max = $max;

        return $this;
    }
}
