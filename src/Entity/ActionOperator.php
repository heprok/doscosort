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
 *      denormalizationContext={"groups"={"action_operator:write"},"disable_type_enforcement"=true}
 * )
 */
class ActionOperator
{
    /**
     * @ORM\Id
     * @ORM\Column(type="smallint", name="id")
     * @Groups({"action_operator:read", "action_operator:write"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=128,
     *      options={"comment":"Название действия"})
     * @Groups({"action_operator:read", "action_operator:write"})
     */
    private $name;

    public function __construct(int $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }    
    
    public function setCode(int $code): self
    {
        $this->code = $code;

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
