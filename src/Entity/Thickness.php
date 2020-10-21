<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ThicknessRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ThicknessRepository::class)
 * @ORM\Table(name="ds.thickness", 
 *      options={"comment":"Справочник толщин"})
 */
class Thickness
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="smallint", 
     *      options={"comment":"Номинальная толщина"})
     */
    private int $nom;

    /**
     * @ORM\Column(type="smallint", 
     *      options={"comment":"Минимальная толщина"})
     */
    private int $min;

    /**
     * @ORM\Column(type="smallint", 
     *      options={"comment":"Максимальная толщина", "check":"min <= max"})
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
