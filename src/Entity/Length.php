<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\LengthRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LengthRepository::class)]
#[ORM\Table(schema: "ds", name: "length", options: ["comment" => "Длины"])]
class Length
{

    #[ORM\Id]
    #[ORM\Column(type: "smallint", options: ["comment" => "Длина"])]
    #[Groups(["length:read", "length:write"])]
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
