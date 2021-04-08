<?php

namespace App\Entity;

use App\Repository\PocketDistanceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PocketDistanceRepository::class)
 * @ORM\Table(name="ds.pocket_distance",
 *      options={"comment":"Дистанция до карманов"})
 */
class PocketDistance
{
    /**
     * @ORM\Id
     * @ORM\Column(type="smallint")
     */
    private $pocket;

    /**
     * @ORM\Column(type="smallint")
     */
    private $integral;

    /**
     * @ORM\Column(type="smallint")
     */
    private $decimal;

    public function getPocket(): ?int
    {
        return $this->pocket;
    }

    public function getIntegral(): ?int
    {
        return $this->integral;
    }

    public function setIntegral(int $integral): self
    {
        $this->integral = $integral;

        return $this;
    }

    public function getDecimal(): ?int
    {
        return $this->decimal;
    }

    public function setDecimal(int $decimal): self
    {
        $this->decimal = $decimal;

        return $this;
    }
}
