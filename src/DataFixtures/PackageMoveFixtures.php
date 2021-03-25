<?php

namespace App\DataFixtures;

use App\Entity\PackageMove;
use App\Entity\Package;
use App\Entity\PackageLocation;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PackageMoveFixtures extends Fixture implements DependentFixtureInterface
{
    // const COUNT_PACKAGE = 1000;
    const COUNT_PACKAGE_LOCATION = 4;
    const COUNT_PACKAGE_MOVE = 1000;
    public function load(ObjectManager $manager)
    {
        $packages = $manager->getRepository(Package::class)->findAll();
        $arrLocation = [];
        
        for ($i=0; $i < self::COUNT_PACKAGE_LOCATION; $i++) { 
            $location = new PackageLocation();
            $location->setName('Локация ' . $i + 1);
            $arrLocation[] = $location;
            $manager->persist($location);
        }
        $randomDatesTimestamp = AppFixtures::getRandomDatetime(self::COUNT_PACKAGE_MOVE);

        for ($i = 0; $i < self::COUNT_PACKAGE_MOVE; $i++) {
            $move = new PackageMove();

            $date = new DateTime();
            $date->setTimestamp($randomDatesTimestamp[$i]);
            $move->setDrec($date);
            $move->setPackage($packages[array_rand($packages)]);
            $move->setSrc($arrLocation[array_rand($arrLocation)]);
            $move->setDst($arrLocation[array_rand($arrLocation)]);
            $manager->persist($move);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PackageFixtures::class,
        ];
    }
}
