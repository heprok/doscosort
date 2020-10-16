<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LengthRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LengthRepository::class)
 * @ORM\Table(name="ds.length", 
 *      options={"comment":"Справочник длин"})
 */
class Length
{
    /**
     * @ORM\Id
     * @ORM\Column(type="smallint",
     *      options={"comment":"Длина"})
     */
    private int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
