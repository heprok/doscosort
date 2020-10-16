<?php

namespace App\Entity;

use App\Repository\ActionOperatorRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ActionOperatorRepository::class)
 * @ORM\Table(name="ds.action_operator",
 *      options={"comment":"Действия оператора"})
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put", "delete"},
 *      normalizationContext={"groups"={"action_operator:read"}},
 *      denormalizationContext={"groups"={"action_operator:write"}}
 * )
 */
class ActionOperator
{
    /**
     * @ORM\Id
     * @ORM\Column(type="smallint")
     * @Groups({"action_operator:read", "action_operator:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     * @Groups({"action_operator:read", "action_operator:write"})
     */
    private $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
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
