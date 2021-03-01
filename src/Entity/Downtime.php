<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Filter\DateFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\DowntimeRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *      collectionOperations={"get"},
 *      itemOperations={"get"},
 *      normalizationContext={"groups"={"downtime:read"}},
 *      denormalizationContext={"groups"={"downtime:write"}}
 * )
 * @ApiFilter(DateFilter::class, properties={"drecTimestampKey"})
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=DowntimeRepository::class)
 * @ORM\Table(name="ds.downtime",
 *      options={"comment":"Простои"})
 */
class Downtime
{

    private DateTime $drec;

    /**
     * @ORM\Id
     * @ORM\Column(name="drec", type="string",
     *      options={"comment":"Время начала простоя"})
     * @ApiProperty(identifier=true)
     * @Groups({"downtime:read"})
     */
    private $drecTimestampKey;

    /**
     * @ORM\ManyToOne(targetEntity=DowntimeCause::class, cascade={"persist", "refresh"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @Groups({"downtime:read"})
     */
    private $cause;

    /**
     * @ORM\ManyToOne(targetEntity=DowntimePlace::class, cascade={"persist", "refresh"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @Groups({"downtime:read"})
     */
    private $place;

    /**
     * @ORM\Column(type="datetime", nullable=true,
     *      options={"comment":"Время окончания простоя"})
     * @Groups({"downtime:read"})
     */
    private $finish;

    public function getDrecTimestampKey(): ?int
    {
        return strtotime($this->drec->format(DATE_ATOM));
    }

    /**
     * @Groups({"downtime:read"})
     */
    public function getStart(): ?string
    {
        return $this->drec->format(BaseEntity::DATE_FORMAT_DB);
    }

    public function getDrec(): DateTime
    {
        return $this->drec;
    }

    public function setDrec(\DateTimeInterface $drec): self
    {
        $this->drec = $drec;

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

    public function getPlace(): ?DowntimePlace
    {
        return $this->place;
    }

    public function setPlace(?DowntimePlace $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getFinish(): ?\DateTimeInterface
    {
        return $this->finish;
    }

    public function setFinish(?\DateTimeInterface $finish): self
    {
        $this->finish = $finish;

        return $this;
    }

    /**
     * @Groups({"downtime:read"})
     */
    public function getStartTime(): ?string
    {
        return $this->drec->format(BaseEntity::TIME_FOR_FRONT);
    }
    /**
     * @Groups({"downtime:read"})
     */
    public function getEndTime(): ?string
    {
        if (isset($this->finish))
            return $this->finish->format(BaseEntity::TIME_FOR_FRONT);
        else {
            return 'Продолжается';
        }
    }

    /**
     * @Groups({"downtime:read"})
     */
    public function getDurationTime(): ?string
    {
        if (isset($this->finish))
            return $this->finish->diff($this->drec)->format(BaseEntity::INTERVAL_TIME_SECOND_FORMAT);
        else {
            return 'Продолжается';
        }
    }

    public function getDurationInterval(): ?DateInterval
    {
        if (isset($this->finish))
            return $this->finish->diff($this->drec);
        else {
            return null;
        }
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function syncDrecTodrecTimestampKey(LifecycleEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $this->drecTimestampKey = $this->drec->format($platform->getDateTimeFormatString());
    }

    /**
     * @ORM\PostLoad
     */
    public function syncdrecTimestampKeyToDrec(LifecycleEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $this->drec = DateTime::createFromFormat($platform->getDateTimeTzFormatString(), $this->drecTimestampKey) ?:
            \DateTime::createFromFormat($platform->getDateTimeFormatString(), $this->drecTimestampKey);
    }
}
