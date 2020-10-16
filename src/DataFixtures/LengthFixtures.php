<?php

namespace App\DataFixtures;

use App\Entity\Length;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LengthFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $length = new Length(3000);
        $manager->persist($length);

        $length = new Length(3300);
        $manager->persist($length);

        $length = new Length(3600);
        $manager->persist($length);

        $length = new Length(3900);
        $manager->persist($length);

        $length = new Length(4200);
        $manager->persist($length);

        $length = new Length(4500);
        $manager->persist($length);

        $length = new Length(4800);
        $manager->persist($length);

        $length = new Length(5100);
        $manager->persist($length);

        $length = new Length(5400);
        $manager->persist($length);

        $length = new Length(5700);
        $manager->persist($length);

        $length = new Length(6000);
        $manager->persist($length);

        $manager->flush();
    }
}
