<?php

namespace App\DataFixtures;

use App\Entity\Thickness;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ThicknessFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $thickness = new Thickness(25, 25, 27);
        $manager->persist($thickness);        
        
        $thickness = new Thickness(30, 30, 34);
        $manager->persist($thickness);        
        
        $thickness = new Thickness(40, 40, 45);
        $manager->persist($thickness);        

        $thickness = new Thickness(50, 50, 56);
        $manager->persist($thickness);
        
        $manager->flush();
    }
}
