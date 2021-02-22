<?php

namespace App\Entity;

use App\Repository\BreakSheduleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put"},
 *      normalizationContext={"groups"={"break_shedule:read"}},
 *      denormalizationContext={"groups"={"break_shedule:write"}}
 * )
 * @ORM\Entity(repositoryClass=BreakSheduleRepository::class)
 * @ORM\Table(name="ds.break_shedule",
 *  uniqueConstraints={
 *        @UniqueConstraint(name="bredk_shedule_unique", 
 *            columns={"start", "stop"})
 *    },
 *      options={"comment":"График перерывов"})
 */
class BreakShedule
{
    /**
     * @ORM\Id
     * @ApiProperty(identifier=true)
     * @ORM\Column(type="integer", unique=true,
     *      options={"comment":"Начало перерыва в формате HHMM"})
     * @Groups({"break_shedule:read", "break_shedule:write", "downtime_place:read"})
     */
    private int $start;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=true,
     *      options={"comment":"Конец перерыва в формате HHMM"})
     * @Groups({"break_shedule:read", "break_shedule:write", "downtime_place:read"})
     */
    private int $stop;

    /**
     * @ORM\ManyToOne(targetEntity=DowntimePlace::class, cascade={"persist", "refresh"}, inversedBy="breakShedules")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups({"break_shedule:read", "break_shedule:write", "downtime_place:read"})
     */
    private DowntimePlace $place;

    /**
     * @ORM\ManyToOne(targetEntity=DowntimeCause::class, cascade={"persist", "refresh"}, inversedBy="breakShedules")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups({"break_shedule:read", "break_shedule:write", "downtime_place:read"})
     */
    private DowntimeCause $cause;

    public function __construct(int $start, int $stop, DowntimeCause $cause, DowntimePlace $place)
    {
        $this->start = $start;
        $this->stop = $stop;
        $this->cause = $cause;
        $this->place = $place;
    }

    public function getStart(): ?int
    {
        return $this->start;
    }

    public function setStart(int $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getStop(): ?int
    {
        return $this->stop;
    }

    public function setStop(int $stop): self
    {
        $this->stop = $stop;
        
        return $this;
    }

    public function getPlace(): ?DowntimePlace
    {
        return $this->place;
    }

    public function setPlace(?DowntimePlace $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getCause(): ?DowntimeCause
    {
        return $this->cause;
    }

    public function setCause(?DowntimeCause $cause): self
    {
        $this->cause = $cause;

        return $this;
    }
}
