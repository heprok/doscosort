<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Filter\DateFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ORM\Table(name="ds.event")
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiFilter(DateFilter::class, properties: ["drecTimestampKey"])]
#[
    ApiResource(
        collectionOperations: ["get"],
        itemOperations: ["get"],
        normalizationContext: ["groups" => ["event:read"]],
        denormalizationContext: ["groups" => ["event:write"]]
    )
]
#[ApiFilter(SearchFilter::class, properties: ["type" => "partial", "source" => "partial"])]

class Event
{

    private DateTime $drec;

    /**
     * @ORM\Id
     * @ORM\Column(name="drec", type="string",
     *      options={"comment":"Начало события"})
     */
    #[Groups(["event:read"])]
    #[ApiProperty(identifier: true)]
    private $drecTimestampKey;

    /**
     * @ORM\ManyToOne(targetEntity=EventType::class)
     * @ORM\JoinColumn(nullable=false, name="type")
     */
    #[Groups(["event:read"])]
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=EventSource::class)
     * @ORM\JoinColumn(nullable=false, name="source")
     */
    #[Groups(["event:read"])]
    private $source;

    /**
     * @ORM\Column(type="string", length=128)
     */
    #[Groups(["event:read"])]
    private string $text;

    /**
     * @ORM\Column(type="smallint", 
     *      options={"comment", "Код ошибки"})
     */
    #[Groups(["event:read"])]
    private ?int $code;

    public function getDrecTimestampKey(): ?int
    {
        return strtotime($this->drec->format(DATE_ATOM));
    }

    public function getDrec(): DateTime
    {
        return $this->drec;
    }

    #[Groups(["event:read"])]
    public function getStart(): ?string
    {
        return $this->drec->format(BaseEntity::DATETIME_FOR_FRONT);
    }

    #[Groups(["event:read"])]
    public function getStartTime(): ?string
    {
        return $this->drec->format(BaseEntity::TIME_FOR_FRONT);
    }

    public function setDrec(\DateTimeInterface $drec): self
    {
        $this->drec = $drec;

        return $this;
    }

    public function getType(): ?EventType
    {
        return $this->type;
    }

    public function setType(?EventType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSource(): ?EventSource
    {
        return $this->source;
    }

    public function setSource(?EventSource $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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
    public function syncDrecTimestampKeyToDrec(LifecycleEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $this->drec = DateTime::createFromFormat($platform->getDateTimeTzFormatString(), $this->drecTimestampKey) ?:
            \DateTime::createFromFormat($platform->getDateTimeFormatString(), $this->drecTimestampKey) ?:
            \DateTime::createFromFormat(BaseEntity::DATE_SECOND_TIMEZONE_FORMAT_DB, $this->drecTimestampKey);
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
}
