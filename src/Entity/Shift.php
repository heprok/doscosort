<?php

namespace App\Entity;

use App\Repository\ShiftRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use App\Filter\DateFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use DateInterval;
use DatePeriod;
use DateTime;

/**
 * @ApiResource(
 *      collectionOperations={"get"},
 *      itemOperations={"get"},
 *      normalizationContext={"groups"={"shift:read"}},
 *      denormalizationContext={"groups"={"shift:write"}}
 * )
 * @ORM\Entity(repositoryClass=ShiftRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="ds.shift",
 *      options={"comment":"Смены"})
 */
class Shift
{
    private $start;

    /**
     * @ORM\Id
     * @ORM\Column(name="start", type="string",
     *      options={"comment":"Время начала смены"})
     * @ApiProperty(identifier=true)
     * @Groups({"shift:read"})
     * @ApiFilter(DateFilter::class)
     */
    private $startTimestampKey;

    /**
     * @ORM\Column(type="smallint")
     *      options={"comment":"Номер смены"})
     * @Groups({"shift:read"})
     */
    private $number;

    /**
     * @ORM\Column(type="datetime", nullable=true,
     *      options={"comment":"Окончание смены"})
     * @Groups({"shift:read"})
     */
    private $stop;

    /**
     * @ORM\ManyToOne(targetEntity=People::class, cascade={"persist", "remove", "refresh"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @Groups({"shift:read"})
     */
    private $people;

    public function getStartTimestampKey(): ?int
    {
        return strtotime($this->start->format(DATE_ATOM));
    }

    /**
     * @Groups({"shift:read"})
     */
    public function getStart(): ?string
    {
        return $this->start->format(BaseEntity::DATE_FORMAT_DB);
    }
    /**
     * @Groups({"shift:read"})
     */
    public function getStartTime(): ?string
    {
        return $this->start->format(BaseEntity::TIME_FOR_FRONT);
    }
    /**
     * @Groups({"shift:read"})
     */
    public function getEndTime(): ?string
    {
        return $this->stop ? $this->stop->format(BaseEntity::TIME_FOR_FRONT) : 'В работе';
    }

    public function getStartShift(): ?string
    {
        return $this->start->format(BaseEntity::DATETIME_FOR_FRONT);
    }

    public function getStopShift(): ?string
    {
        return $this->stop ? $this->stop->format(BaseEntity::DATETIME_FOR_FRONT) : 'В работе';
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getStop(): ?\DateTimeInterface
    {
        return $this->stop;
    }

    public function setStop(?\DateTimeInterface $stop): self
    {
        $this->stop = $stop;

        return $this;
    }

    public function getPeriod(): DatePeriod
    {
        // $endDate = $this->stop ? $this->stop : new DateTime();
        $period = new DatePeriod($this->start, new DateInterval('P1D'), $this->stop ?? new DateTime()); 
        
        return $period;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function syncStartToStartTimestampKey(LifecycleEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $this->startTimestampKey = $this->start->format($platform->getDateTimeFormatString());
    }

    /**
     * @ORM\PostLoad
     */
    public function syncStartTimestampKeyToStart(LifecycleEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $this->start = DateTime::createFromFormat($platform->getDateTimeTzFormatString(), $this->startTimestampKey) ?: 
            \DateTime::createFromFormat($platform->getDateTimeFormatString(), $this->startTimestampKey) ?: 
                \DateTime::createFromFormat(BaseEntity::DATE_SECOND_TIMEZONE_FORMAT_DB, $this->startTimestampKey);
    }

    public function getPeople(): ?People
    {
        return $this->people;
    }

    public function setPeople(?People $people): self
    {
        $this->people = $people;

        return $this;
    }
}
