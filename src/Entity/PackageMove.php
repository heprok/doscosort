<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\MongoDbOdm\Filter\OrderFilter;
use App\Filter\DateFilter;
use App\Repository\PackageMoveRepository;
use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PackageMoveRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="ds.package_move",
 *      options={"comment":"Информация о передвежениях"})
 */
#[
ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: ["get"],
    normalizationContext: ["groups" => ["package_move:read"]],
    denormalizationContext: ["groups" => ["package_move:write"]]
)]
class PackageMove
{
    private DateTime $drec;

    /**
     * @ORM\Id
     * @ORM\Column(name="drec", type="string",
     *      options={"comment":"Время передвежения пакета"})
     */
    #[Groups(["package_move:read"])]
    #[ApiProperty(identifier:true)]
    private $drecTimestampKey;

    /**
     * @ORM\ManyToOne(targetEntity=PackageLocation::class)
     * @ORM\JoinColumn(referencedColumnName="id", name="src", nullable=false)
     */
    #[Groups(["package:read", "package_move:write","package_move:read"])]
    private $src;

    /**
     * @ORM\ManyToOne(targetEntity=PackageLocation::class)
     * @ORM\JoinColumn(referencedColumnName="id", name="dst", nullable=false)
     */
    #[Groups(["package:read", "package_move:write","package_move:read"])]
    private $dst;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(["package:read", "package_move:write","package_move:read"])]
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Package::class, inversedBy="packageMoves")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(["package_move:write",])]
    private $package;

    public function __construct()
    {
        $this->drec = new DateTime();
    }

    public function getDrecTimestampKey(): ?int
    {
        return strtotime($this->drec->format(DATE_ATOM));
    }

    public function getDrec(): DateTime
    {
        return $this->drec;
    }

    public function setDrec(DateTime $drec): self
    {
        $this->drec = $drec;
        return $this;
    }

    #[Groups(["package:read"])]
    public function getDrecTime(): ?string
    {
        return $this->drec->format(BaseEntity::DATETIME_FOR_FRONT);
    }

    public function getSrc(): ?PackageLocation
    {
        return $this->src;
    }

    public function setSrc(?PackageLocation $src): self
    {
        $this->src = $src;

        return $this;
    }

    public function getDst(): ?PackageLocation
    {
        return $this->dst;
    }

    public function setDst(?PackageLocation $dst): self
    {
        $this->dst = $dst;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPackage(): ?Package
    {
        return $this->package;
    }

    public function setPackage(?Package $package): self
    {
        $this->package = $package;

        return $this;
    }
}
