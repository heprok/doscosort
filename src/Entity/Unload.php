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
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UnloadRepository::class)
 * @ORM\Table(name="ds.unload",
 *      options={"comment":"Выгруженные карманы"})
 * @ApiResource(
 *      collectionOperations={"get"},
 *      itemOperations={"get"},
 *      normalizationContext={"groups"={"unload:read"}},
 *      denormalizationContext={"groups"={"unload:write"}}
 * )
 * @ApiFilter(DateFilter::class, properties={"drecTimestampKey"})
 * @ORM\HasLifecycleCallbacks()
 */
class Unload
{

    private DateTime $drec;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", 
     *      options={"comment":"Время выгрузки"})
     * @Groups({"unload:read"})
     */
    private $drecTimestampKey;


    /**
     * @ORM\Column(type="string", length=255, 
     *      options={"comment":"Название качеств"})
     * @Groups({"unload:read"})
     */
    private $qualities;

    /**
     * @ORM\Column(type="smallint", 
     *      options={"comment":"Количество досок"})
     * @Groups({"unload:read"})
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"unload:read"})
     */
    private $group;

    public function getId(): ?int
    {
        return $this->id;
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

}
