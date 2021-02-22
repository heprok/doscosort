<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LengthRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=LengthRepository::class)
 * @ORM\Table(name="ds.length",
 *      options={"comment":"Длины"})
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put", "delete"},
 *      normalizationContext={"groups"={"length:read"}},
 *      denormalizationContext={"groups"={"length:write"}, "disable_type_enforcement"=true}
 * )
 */
class Length
{
    /**
     * @ORM\Id
     * @ORM\Column(type="smallint",
     *      options={"comment":"Длина"})
     * @Groups({"length:read", "length:write"})
     */
    private int $value;

    public function __toString()
    {
        return strval($this->value);
    }

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
