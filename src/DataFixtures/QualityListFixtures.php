<?php

namespace App\DataFixtures;

use App\Entity\QualityList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QualityListFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $qualityList = new QualityList('Основной');
        $manager->persist($qualityList);
        $arrayQualityList[] = $qualityList;

        $manager->flush();

        $manager->flush();
    }
}
