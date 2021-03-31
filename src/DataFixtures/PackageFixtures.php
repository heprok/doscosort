<?php

namespace App\DataFixtures;

use App\Entity\Package;
use App\Entity\Quality;
use App\Entity\Species;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DoctrineExtensions\Types\Leam;

class PackageFixtures extends Fixture implements DependentFixtureInterface
{
    const COUNT_PACKAGE = 1000;

    public function load(ObjectManager $manager)
    {
        $species = $manager->getRepository(Species::class)->findAll();
        $qualities = $manager->getRepository(Quality::class)->findAll();
        $randomDatesTimestamp = AppFixtures::getRandomDatetime(self::COUNT_PACKAGE);
        $boards = [
            new Leam(4000, 4),
            new Leam(5000, 1),
            new Leam(6000, 10),
            new Leam(7000, 2),
        ];

        for ($i = 0; $i < self::COUNT_PACKAGE; $i++) {
            $package = new Package();

            $date = new DateTime();
            $date->setTimestamp($randomDatesTimestamp[$i]);
            $package->setDrec($date);
            $package->setWidth(rand(10, 40));
            $package->setThickness(rand(100, 600));
            $package->setSpecies($species[rand(0, /* count($species) - 1 */ 2)]);
            $package->setBoards($boards);
            $package->setDry($i % 2);
            $package->setQualities($qualities[array_rand($qualities)]->getName());
            $manager->persist($package);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SpeciesFixtures::class,
            QualityFixtures::class,
        ];
    }
}
