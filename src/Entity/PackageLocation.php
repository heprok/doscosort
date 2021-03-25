<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PackageLocationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PackageLocationRepository::class)
 * @ORM\Table(name="ds.package_location",
 *      options={"comment":"Локация пакета"})
 */
#[ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: ["get", 'put'],
    normalizationContext: ["groups" => ["package_location:read"]],
    denormalizationContext: ["groups" => ["package_location:write"]]
)]
class PackageLocation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["package_location:write", "package_location:read"])]
    private int $id;

    /**
     * @ORM\Column(type="string", length=255,
     *      options={"comment":"Название"})
     */
    #[Groups(["package_location:write", "package_location:read"])]
    private string $name;

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
