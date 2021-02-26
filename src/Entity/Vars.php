<?php

namespace App\Entity;

use App\Repository\VarsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiFilter;

/**
 * @ORM\Entity(repositoryClass=VarsRepository::class)
 * @ORM\Table(name="ds.vars")
 * @ApiResource(
 *      collectionOperations={"get"},
 *      itemOperations={"get"},
 *      normalizationContext={"groups"={"vars:read"}},
 *      denormalizationContext={"groups"={"vars:write"}}
 * )
 * @ApiFilter(SearchFilter::class, properties={"name": "exact"})
 */
class Vars
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string",length=64,
     *      options={"comment":"Ключ"})
     * @ApiProperty(identifier=true)
     * @Groups({"vars:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64, nullable=true, 
     *      options={"comment":"Значение"})
     * @Groups({"vars:read"})
     */
    private $value;

    public function __construct(string $name, string $value = '')
    {
        $this->name = $name;
        $this->value = $value;
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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
