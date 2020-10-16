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
    const DATE_FORMAT_DB = 'Y-m-d\TH:i:sP';
    const DATE_FOR_FRONT = 'd.m.Y H:i:s';
    const DATE_FOR_FRONT_TIME = 'H:i:s';

    private $start;

    /**
     * @ORM\Id
     * @ORM\Column(name="start", type="string")
     * @ApiProperty(identifier=true)
     *      options={"comment":"Время начала смены", "default":NOW()}})
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
     * @ORM\Column(type="datetime", nullable=true)
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
        return $this->start->format(self::DATE_FORMAT_DB);
    }
    /**
     * @Groups({"shift:read"})
     */
    public function getStartTime(): ?string
    {
        return $this->start->format(self::DATE_FOR_FRONT_TIME);
    }
    /**
     * @Groups({"shift:read"})
     */
    public function getEndTime(): ?string
    {
        if (isset($this->stop))
            return $this->stop->format(self::DATE_FOR_FRONT_TIME);
        else{
            return 'В работе';
        }
    }

    public function getStartShift(): ?string
    {
        return $this->start->format(self::DATE_FOR_FRONT);
    }

    public function getStopShift(): ?string
    {
        if ($this->stop)
            return $this->stop->format(self::DATE_FOR_FRONT);
        else
            return 'В работе';
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
        $this->start = \DateTime::createFromFormat($platform->getDateTimeFormatString(), $this->startTimestampKey);
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
