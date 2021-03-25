<?php

namespace App\Entity;

use App\Repository\PocketEventRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @ORM\Entity(repositoryClass=PocketEventRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="ds.pocket_event")
 */
#[
ApiResource(
    collectionOperations: ["get"],
    itemOperations: ["get"],
    normalizationContext: ["groups" => ["event:read"]],
    denormalizationContext: ["groups" => ["event:write"]]
)]
class PocketEvent
{
    const DATE_FORMAT_DB = 'Y-m-d\TH:i:sP';

    private DateTime $drec;

    /**
     * @ORM\Id
     * @ORM\Column(name="drec", type="string",
     *      options={"comment":"Время события"})
     */
    #[ApiProperty(identifier: true)]
    #[Groups(["event:read"])]
    private $drecTimestampKey;


    /**
     * @ORM\Column(type="smallint",
     *      options={"comment":"Номер кармана"})
     */
    private $number_pocket;

    /**
     * @ORM\ManyToOne(targetEntity=PocketEventType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    public function getDrecTimestampKey(): ?int
    {
        return strtotime($this->drec->format(DATE_ATOM));
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

    public function getNumberPocket(): ?int
    {
        return $this->number_pocket;
    }

    public function setNumberPocket(int $number_pocket): self
    {
        $this->number_pocket = $number_pocket;

        return $this;
    }

    public function getType(): ?PocketEventType
    {
        return $this->type;
    }

    public function setType(?PocketEventType $type): self
    {
        $this->type = $type;

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
            \DateTime::createFromFormat($platform->getDateTimeFormatString(), $this->drecTimestampKey);
    }
}
