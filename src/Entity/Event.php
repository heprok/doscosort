<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ApiResource(
 *      collectionOperations={"get"},
 *      itemOperations={"get"},
 *      normalizationContext={"groups"={"event:read"}},
 *      denormalizationContext={"groups"={"event:write"}}
 * )
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="ds.event")
 */
class Event
{
    const DATE_FORMAT_DB = 'Y-m-d\TH:i:sP';

    private DateTime $drec;
    
    /**
     * @ORM\Id
     * @ORM\Column(name="drec", type="string")
     * @ApiProperty(identifier=true)
     *      options={"comment":"Время начала простоя", "default":NOW()}})
     * @Groups({"event:read"})
     */
    private $drecTimestampKey;

    /**
     * @ORM\ManyToOne(targetEntity=EventType::class)
     * @ORM\JoinColumn(nullable=false, name="type")
     * @Groups({"event:read"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=EventSource::class)
     * @ORM\JoinColumn(nullable=false, name="source")
     * @Groups({"event:read"})
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=128)
     * @Groups({"event:read"})
     */
    private $text;

    public function getDrecTimestampKey(): ?int
    {
        return strtotime($this->drec->format(DATE_ATOM));
    }

    public function getDrec(): DateTime
    {
        return $this->drec;
    }
    /**
     * @Groups({"event:read"})
     */
    public function getStart(): ?string
    {
        return $this->drec->format(self::DATE_FORMAT_DB);
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
        $this->drec = \DateTime::createFromFormat($platform->getDateTimeTzFormatString(), $this->drecTimestampKey);
    }
}
