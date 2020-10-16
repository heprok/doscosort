<?php

namespace App\DataFixtures;

use App\Entity\Downtime;
use App\Entity\DowntimeCause;
use App\Entity\DowntimePlace;
use DateInterval;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DowntimeFixtures extends Fixture
{
    const COUNT_CAUSE = 10;
    const COUNT_PLACE = 10;
    const COUNT_DOWNTIME = 100;

    public function load(ObjectManager $manager)
    {
        $arrPlace = [];
        $arrCause = [];
        for ($i=1; $i <= self::COUNT_CAUSE; $i++) { 
            $cause = new DowntimeCause('Причина ' . $i);
            $arrCause[] = $cause;

            $manager->persist($cause);
        }
        
        for ($i=1; $i <= self::COUNT_PLACE; $i++) { 
            $place = new DowntimePlace("Место  " . $i);
            $arrPlace[] = $place;
            $manager->persist($place);
        }

        for ($i=0; $i < self::COUNT_DOWNTIME; $i++) { 
            $downtime = new Downtime();
            
            $randomDateTimestamp = AppFixtures::randomDate();
            $startTime = new DateTime();
            $startTime->setTimestamp($randomDateTimestamp);
            $downtime->setDrec($startTime);

            $stopTime = new DateTime();
            $stopTime->setTimestamp($randomDateTimestamp + 3 * 60);
            $downtime->setFinish($stopTime);

            $downtime->setCause($arrCause[rand(0, self::COUNT_CAUSE - 1)]);
            $downtime->setPlace($arrPlace[rand(0, self::COUNT_PLACE - 1)]);
            $manager->persist($downtime);
        }
        
        $manager->flush();
    }
}