<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DowntimePlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DowntimePlaceRepository::class)
 * @ORM\Table(name="ds.downtime_place",
 *      options={"comment":"Места простоя"})
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put"},
 *      normalizationContext={"groups"={"downtime_place:read"}},
 *      denormalizationContext={"groups"={"downtime_place:write"}, "disable_type_enforcement"=true}
 * )
 */
class DowntimePlace
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", name="id")
     * @Groups({"downtime_place:read", "downtime_place:write"})
     */
    private int $code;

    /**
     * @ORM\Column(type="string", length=128, name="text",
     *      options={"comment":"Название места"})
     * @Groups({"downtime_place:read", "downtime_place:write", "downtime:read", "break_shedule:read"})
     */
    private string $name;

    /**
     * @ORM\Column(type="boolean",
     *      options={"comment":"Используется", "default":"true"})
     * @Groups({"downtime_place:read", "downtime_place:write", "downtime:read"})
     */
    private bool $enabled = true;

    /**
     * @ORM\ManyToOne(targetEntity=DowntimeLocation::class, inversedBy="downtimePlaces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"downtime_place:read", "downtime_place:write"})
     */
    private $location;

    /**
     * @ORM\OneToMany(targetEntity=BreakShedule::class, mappedBy="place")
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

    public function getEnabled() :bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled) : self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getLocation(): ?DowntimeLocation
    {
        return $this->location;
    }

    public function setLocation(?DowntimeLocation $location): self
    {
        $this->location = $location;

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
            $breakShedule->setPlace($this);
        }

        return $this;
    }

    public function removeBreakShedule(BreakShedule $breakShedule): self
    {
        if ($this->breakShedules->removeElement($breakShedule)) {
            // set the owning side to null (unless already changed)
            if ($breakShedule->getPlace() === $this) {
                $breakShedule->setPlace(null);
            }
        }

        return $this;
    }
}