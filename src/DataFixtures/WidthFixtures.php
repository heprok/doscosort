<?php

namespace App\DataFixtures;

use App\Entity\Width;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WidthFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $width = new Width(75, 75, 85);
        $manager->persist($width);        
        
        $width = new Width(100, 100, 112);
        $manager->persist($width);        
        
        $width = new Width(150, 150, 165);
        $manager->persist($width);        

        $manager->flush();
    }
}
