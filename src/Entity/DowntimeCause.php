<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DowntimeCauseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DowntimeCauseRepository::class)
 * @ORM\Table(name="ds.downtime_cause",
 *      options={"comment":"Причины простоя"})
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put"},
 *      normalizationContext={"groups"={"downtime_cause:read"}},
 *      denormalizationContext={"groups"={"downtime_cause:write"}, "disable_type_enforcement"=true}
 * )
 */
class DowntimeCause
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", name="id")
     * @Groups({"downtime_cause:read","downtime_cause:write"})
     */
    private int $code;

    /**
     * @ORM\Column(type="string", length=128, name="text",
     *      options={"comment":"Название причины"})
     * @Groups({"downtime_cause:read", "downtime_cause:write", "downtime:read", "break_shedule:read"})
     */
    private string $name;

    /**
     * @ORM\Column(type="boolean",
     *      options={"comment":"Используется", "default":"true"})
     * @Groups({"downtime_cause:read", "downtime_cause:write", "downtime:read"})
     */
    private bool $enabled = true;

    /**
     * @ORM\ManyToOne(targetEntity=DowntimeGroup::class, inversedBy="downtimeCauses")
     * @ORM\JoinColumn(nullable=false, name="group_id")
     * @Groups({"downtime_cause:read", "downtime_cause:write"})
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity=BreakShedule::class, mappedBy="cause")
     */
    private $breakShedules;

    public function __construct(int $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
        $this->breakShedules = new ArrayCollection();
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

    public function __toString()
    {
        return $this->getName();
    }

    public function getName(): string
    {
        return $this->name ?? '';
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getGroups(): ?DowntimeGroup
    {
        return $this->groups;
    }

    public function setGroups(?DowntimeGroup $groups): self
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @return Collection|BreakShedule[]
     */
    public function getBreakShedules(): Collection
    {
        return $this->breakShedules;
    }

    public function addBreakShedule(BreakShedule $breakShedule): self
    {
        if (!$this->breakShedules->contains($breakShedule)) {
            $this->breakShedules[] = $breakShedule;
            $breakShedule->setCause($this);
        }

        return $this;
    }

    public function removeBreakShedule(BreakShedule $breakShedule): self
    {
        if ($this->breakShedules->removeElement($breakShedule)) {
            // set the owning side to null (unless already changed)
            if ($breakShedule->getCause() === $this) {
                $breakShedule->setCause(null);
            }
        }

        return $this;
    }
}
