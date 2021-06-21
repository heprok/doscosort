<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Tlc\ManualBundle\Entity\Group as BaseGroup;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(schema: "ds", name: '"group"',  options: ["comment" => "Группы параметров досок"])]

class Group extends BaseGroup
{
    #[ORM\ManyToOne(targetEntity: Species::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["unload:read"])]
    protected $species;
}
