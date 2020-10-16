<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const startDate = '2020-10-01';
    const endDate =  '2020-10-10';

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }


    static public function randomDate()
    {
        $min = strtotime(self::startDate);
        $max = strtotime(self::endDate);

        $val = rand($min, $max);
        return $val;
    }
}