<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QualityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=QualityRepository::class)
 * @ORM\Table(name="ds.quality", 
 *      options={"comment":"Качества доски"})
 */
#[
    ApiResource(
        collectionOperations: ["get"],
        itemOperations: ["get"],
        normalizationContext: ["groups" => ["quality:read"]],
        denormalizationContext: ["groups" => ["quality:write"]]
    )
]
class Quality
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="smallint",
     *      options={"comment":"ID доски, 1 бит"})
     */
    #[Groups(['quality:read'])]
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
    #[Groups(['quality:read'])]
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
