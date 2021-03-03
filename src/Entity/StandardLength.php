<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\StandardLengthRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=StandardLengthRepository::class)
 * @ORM\Table(name="ds.standard_length",
 *      options={"comment":"Cтандартные длины"})
 */
#[ApiResource(
    collectionOperations: [
        'get' => ['method' => 'GET', 'path' => '/lengths'],
        'post' => ['method' => 'POST', 'path' => '/lengths']
    ], 
    itemOperations: [
        'get' => ['method' => 'GET', 'path' => '/lengths/{standard}'],
        'put' => ['method' => 'PUT', 'path' => '/lengths/{standard}'],
        'delete' => ['method' => 'DELETE', 'path' => '/lengths/{standard}'],
    ],
    normalizationContext: [
        "groups" => ['standard_length:read']
    ],
    denormalizationContext: [
        'groups' => ['standard_length:write'],
        'disable_type_enforcement' => true
    ],
)]
class StandardLength
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="smallint")
     */
    #[Groups(["standard_length:read", "standard_length:write"])]
    private int $standard;

    /**
     * @ORM\Column(type="smallint",
     *      options={"comment":"Минимальная граница диапзаона не включая, мм"})
     */
    #[Groups(["standard_length:read", "standard_length:write"])]
    private int $minimum;

    /**
     * @ORM\Column(type="smallint",
     *      options={"comment":"Максимальная граница диапзаона не включая, мм"})
     */
    #[Groups(["standard_length:read", "standard_length:write"])]
    private int $maximum;

    public function __construct(int $standard)
    {
        $this->standard = $standard;
    }

    public function getStandard(): ?int
    {
        return $this->standard;
    }

    public function setStandard(int $standard): self
    {
        $this->standard = $standard;

        return $this;
    }

    public function getMinimum(): ?int
    {
        return $this->minimum;
    }

    public function setMinimum(int $minimum): self
    {
        $this->minimum = $minimum;

        return $this;
    }    
    
    public function getMaximum(): ?int
    {
        return $this->maximum;
    }

    public function setMaximum(int $maximum): self
    {
        $this->maximum = $maximum;

        return $this;
    }
}