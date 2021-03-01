<?php

namespace App\Entity;

use App\Repository\ShiftSheduleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\ORM\Mapping\UniqueConstraint;
/**
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put"},
 *      normalizationContext={"groups"={"shift_shedule:read"}},
 *      denormalizationContext={"groups"={"shift_shedule:write"}}
 * )
 * @ORM\Entity(repositoryClass=ShiftSheduleRepository::class)
 * @ORM\Table(name="ds.shift_shedule",
 *      uniqueConstraints={
 *        @UniqueConstraint(name="shift_shedule_unique", 
 *            columns={"start", "stop"})
 *    },
 *      options={"comment":"График сменов"})
 */
class ShiftShedule
{
    /**
     * @ORM\Id
     * @ApiProperty(identifier=true)
     * @ORM\Column(type="integer",  unique=true,
     *      options={"comment":"Начало смены"})
     * @Groups({"shift_shedule:read", "shift_shedule:write"})
     */
    private int $start;

    /**
     * @ORM\Column(type="integer", unique=true,
     *      options={"comment":"Конец смены"})
     * @Groups({"shift_shedule:read", "shift_shedule:write"})
     */
    private int $stop;

    /**
     * @ORM\Column(type="string", length=128,
     *      options={"comment":"Наименование смены"})
     * @Groups({"shift_shedule:read", "shift_shedule:write"})
     */
    private string $name;


    public function __construct(string $startTime, string $stopTime, string $name){
        $this->start = (int)str_replace(':', '', $startTime);
        $this->stop = (int)str_replace(':', '', $stopTime);
        $this->name = $name;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getStop(): int
    {
        return $this->stop;
    }

    public function setStart(int $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function setStop(int $stop): self
    {
        $this->stop = $stop;

        return $this;
    }

    #[Groups(["shift_shedule:read"])]
    public function getStopTime(): string
    {
        $stop = BaseEntity::int2time($this->stop);
        return $stop->format(BaseEntity::INTERVAL_TIME_FORMAT);
    }

    #[Groups(["shift_shedule:read"])]
    public function getStartTime(): string
    {
        $start = BaseEntity::int2time($this->start);
        return $start->format(BaseEntity::INTERVAL_TIME_FORMAT);
    }    

    #[Groups(["shift_shedule:write"])]
    public function setStopTime(string $stop): self
    {
        $this->stop = (int)str_replace(':', '', $stop);
        return $this;
    }

    #[Groups(["shift_shedule:write"])]
    public function setStartTime(string $start): self
    {
        $this->start = (int)str_replace(':', '', $start);

        return $this;
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
