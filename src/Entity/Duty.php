<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DutyRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DutyRepository::class)
 * @ORM\Table(name="ds.duty",
 *      options={"comment":"Список должностей"})
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put", "delete"},
 *      normalizationContext={"groups"={"duty:read"}},
 *      denormalizationContext={"groups"={"duty:write"}}
 * )
 */
class Duty
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", name="id", length=2,
     *      options={"fixed":"true"})
     * @Groups({"duty:read", "duty:write"})
     */
    private string $code;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"duty:read", "duty:write", "people:read"})
     */
    private string $name;

    public function __construct(?string $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
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
