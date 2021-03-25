<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ErrorRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ErrorRepository::class)
 * @ORM\Table(name="ds.error",
 * options={"comment":"Ошибки"})
 */
#[
ApiResource(
    collectionOperations: ["get"],
    itemOperations: ["get", "post"],
    normalizationContext: ["groups" => ["error:read"]],
    denormalizationContext: ["groups" => ["error:write"]]
)]
class Error
{
    /**
     * @ORM\Id
     * @ORM\Column(type="smallint", 
     *      options={"comment":"Код ошибки"})
     */
    #[Groups(["error:read"])]
    private int $id;

    /**
     * @ORM\Column(type="string", length=128, 
     *      options={"comment":"Текст ошибки"})
     */
    #[Groups(["error:read", "error:write"])]
    private string $text;

    public function __construct(int $id, string $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
