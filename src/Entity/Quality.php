<?php

namespace App\Entity;

use App\Repository\QualityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QualityRepository::class)
 * @ORM\Table(name="ds.quality", 
 *      options={"comment":"Качества доски"})
 */
class Quality
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="smallint",
     *      options={"comment":"ID доски, 1 бит"})
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=QualityList::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private QualityList $list;

    /**
     * @ORM\Column(type="string", length=32,
     *      options={"comment":"Название качества"})
     */
    private string $name;

    public function __construct(QualityList $list, string $name)
    {
        $this->list = $list;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getList(): ?QualityList
    {
        return $this->list;
    }

    public function setList(?QualityList $list): self
    {
        $this->list = $list;

        return $this;
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
