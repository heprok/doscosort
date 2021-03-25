<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UnloadRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use DateTime;
use App\Filter\DateFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @ORM\Entity(repositoryClass=UnloadRepository::class)
 * @ORM\Table(name="ds.unload",
 *      options={"comment":"Выгруженные карманы"})
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiFilter(DateFilter::class, properties: ["drecTimestampKey"])]
#[
    ApiResource(
        collectionOperations: ["get"],
        itemOperations: ["get"],
        normalizationContext: ["groups" => ["unload:read"]],
        denormalizationContext: ["groups" => ["unload:write"]]
    )
]
class Unload
{

    private DateTime $drec;

    /**
     * @ORM\Id
     * @ORM\Column(name="drec", type="string", 
     *      options={"comment":"Время выгрузки"})
     */
    #[Groups(["unload:read"])]
    private string $drecTimestampKey;


    /**
     * @ORM\Column(type="string", length=255, 
     *      options={"comment":"Название качеств"})
     */
    #[Groups(["unload:read"])]
    private string $qualities;

    /**
     * @ORM\Column(type="smallint", 
     *      options={"comment":"Количество досок"})
     */
    #[Groups(["unload:read"])]
    private int $amount;

    /**
     * @ORM\Column(type="smallint", 
     *      options={"comment":"Карман"})
     */
    #[Groups(["unload:read"])]
    private int $pocket;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class)
     * @ORM\JoinColumn(nullable=false, name="group")
     */
    #[Groups(["unload:read"])]
    private Group $group;

    /**
     * @ORM\Column(type="float",
     *      options={"comment":"Объём выгруженного кармана"})
     */
    #[Groups(["unload:read"])]
    private float $volume;

    public function getDrecTimestampKey(): ?int
    {
        return strtotime($this->drec->format(DATE_ATOM));
    }

    public function getDrec(): DateTime
    {
        return $this->drec;
    }

    /**
     */
    #[Groups(["unload:read"])]
    public function getTime(): ?string
    {
        return $this->drec->format(BaseEntity::TIME_FOR_FRONT);
    }

    public function setDrec(\DateTimeInterface $drec): self
    {
        $this->drec = $drec;

        return $this;
    }

    public function getQualities(): ?string
    {
        return $this->qualities;
    }

    public function setQualities(string $qualities): self
    {
        $this->qualities = $qualities;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPocket(): ?int
    {
        return $this->pocket;
    }

    public function setPocket(int $pocket): self
    {
        $this->pocket = $pocket;

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): self
    {
        $this->group = $group;

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

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(float $volume): self
    {
        $this->volume = $volume;

        return $this;
    }
}
