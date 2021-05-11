<?php

namespace App\Entity;

use App\Repository\PeopleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PeopleRepository::class)
 * @ORM\Table(name="ds.people",
 *      options={"comment":"Люди"})
 */
#[
ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: ["get", "put", "delete"],
    normalizationContext: ["groups" => ["people:read"]],
    denormalizationContext: ["groups" => ["people:write"]]
)]
class People
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    #[Groups(["people:read", "shift:read"])]
    private int $id;

    /**
     * @ORM\Column(type="string", length=30,
     *      options={"comment":"Фамилия"})
     */
    #[Groups(["people:write", "people:read", "shift:read"])]
    private string $fam;

    /**
     * @ORM\Column(type="string", length=30, nullable=true,
     *      options={"comment":"Имя"})
     */
    #[Groups(["people:write", "people:read", "shift:read"])]
    private ?string $nam;

    /**
     * @ORM\Column(type="string", length=30, nullable=true,
     *      options={"comment":"Отчество"})
     */
    #[Groups(["people:write", "people:read", "shift:read"])]
    private ?string $pat;

    /**
     * @ORM\ManyToMany(targetEntity=Duty::class)
     * @ORM\JoinTable(name="ds.people_to_duty")
     */
    #[Groups(["people:write", "people:read"])]
    private $duty;

    public function __construct(string $fam, ?string $nam = null, ?string $pat = null)
    {
        $this->fam = $fam;
        $this->nam = $nam;
        $this->pat = $pat;
        $this->duty = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFam(): ?string
    {
        return $this->fam;
    }

    public function setFam(string $fam): self
    {
        $this->fam = $fam;

        return $this;
    }

    public function getNam(): ?string
    {
        return $this->nam;
    }

    public function setNam(?string $nam): self
    {
        $this->nam = $nam;

        return $this;
    }

    public function getPat(): ?string
    {
        return $this->pat;
    }

    public function setPat(?string $pat): self
    {
        $this->pat = $pat;

        return $this;
    }

    #[Groups(["people:read", "shift:read"])]
    public function getFio(): ?string
    {
        $fio = $this->fam . ' ' . mb_substr($this->nam, 0, 1) . '.' . mb_substr($this->pat, 0, 1) . '.';
        return $fio;
    }

    #[Groups(["people:read", "shift:read"])]
    public function getDutiesString(): ?string
    {
        $duties = [];
        foreach ($this->duty as $duty) {
            $duties[] = $duty->getName();
        }
        return implode(', ', $duties);
    }

    public function getFullFio(): ?string
    {
        return $this->fam . ' ' . ($this->nam ?? '') . ' ' . ($this->pat ?? '');
    }

    /**
     * @return Collection|Duty[]
     */
    public function getDuty(): Collection
    {
        return $this->duty;
    }

    public function addDuty(Duty $duty): self
    {
        if (!$this->duty->contains($duty)) {
            $this->duty[] = $duty;
        }

        return $this;
    }

    public function removeDuty(Duty $duty): self
    {
        $this->duty->removeElement($duty);

        return $this;
    }
}
