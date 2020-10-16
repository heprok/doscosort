<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\DowntimeRepository;
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
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=DowntimeRepository::class)
 * @ORM\Table(name="ds.downtime")
 * options={"comment":"Простои"})
 */
class Downtime
{
    const DATE_FORMAT_DB = 'Y-m-d\TH:i:sP';

    private DateTime $drec;

    /**
     * @ORM\Id
     * @ORM\Column(name="drec", type="string")
     * @ApiProperty(identifier=true)
     * options={"comment":"Время начала простоя", "default":NOW()}})
     * @Groups({"downtime:read"})
     */
    private $drecTimestampKey;

    /**
     * @ORM\ManyToOne(targetEntity=DowntimeCause::class, cascade={"persist", "refresh"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     * options={"comment":"Причина простоя"}
     * @Groups({"downtime:read"})
     */
    private $cause;

    /**
     * @ORM\ManyToOne(targetEntity=DowntimePlace::class, cascade={"persist", "refresh"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     * options={"comment":"Место простоя"}
     * @Groups({"downtime:read"})
     */
    private $place;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * options={"comment":"Время окончания простоя"}
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
        return $this->drec->format(self::DATE_FORMAT_DB);
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
        $this->drec = \DateTime::createFromFormat($platform->getDateTimeFormatString(), $this->drecTimestampKey);
    }
}
