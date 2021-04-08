<?php

namespace App\DataFixtures;

use App\Entity\Unload;
use App\Entity\UnloadList;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UnloadFixtures extends Fixture
{
    const COUNT_UNLOAD = 10;
    public function load(ObjectManager $manager)
    {
        $array_group = $manager->getRepository(Group::class)->findAll();
        $randomDatesTimestamp = AppFixtures::getRandomDatetime(self::COUNT_UNLOAD);
        for ($i = 0; $i < self::COUNT_UNLOAD; $i++) {
            $unload = new Unload();
            $drec = new DateTime();
            $drec->setTimestamp($randomDatesTimestamp[$i]);
            $unload->setDrec($drec);
            $unload->setQualities($i % 3 ? 'NK' : ($i % 2 ? '1-4' : '4-2'));
            $unload->setAmount(rand(0, 200));
            $unload->setGroup($array_group[array_rand($array_group)]);
            $unload->setVolume(rand(0, 100));

            // $manager->persist($unload);
        }

        // $manager->flush();
    }

}
