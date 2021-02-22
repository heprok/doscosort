<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\QualityListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QualityListRepository::class)
 * @ORM\Table(name="ds.quality_list", 
 *      options={"comment":"Списки качеств"})
 */
class QualityList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="smallint", 
     *      options={"check":"BETWEEN 0 AND 15", "unsigned":true})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=32,
     *      options={"comment":"Название списка"})
     */
    private string $name;

    public function __toString()
    {
        return strval($this->id);
    }

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
