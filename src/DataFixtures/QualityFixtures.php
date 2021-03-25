<?php

namespace App\DataFixtures;

use App\Entity\Quality;
use App\Entity\QualityList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QualityFixtures extends Fixture implements DependentFixtureInterface
{
    const COUNT_QUALITY = 10;

    public function load(ObjectManager $manager)
    {
        $qualitieList = $manager->getRepository(QualityList::class)->findAll();
        for ($i = 0; $i < self::COUNT_QUALITY; $i++) {
            $quality = new Quality($qualitieList[array_rand($qualitieList)], 'Качество ' . $i + 1);
            $manager->persist($quality);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            QualityListFixtures::class,
        ];
    }
}
