<?php

namespace App\DataFixtures;

use App\Entity\PocketEvent;
use App\Entity\PocketEventType;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PocketEventFixtures extends Fixture
{
    const COUNT_EVENT = 50;
    const COUNT_POCKET = 16;

    public function load(ObjectManager $manager)
    {
        $arr_pocket_event_type = [];

        $event_type = new PocketEventType('s', 'Начало выгрузки кармана');
        $manager->persist($event_type);
        $arr_pocket_event_type[] = $event_type;

        $event_type = new PocketEventType('e', 'Конец выгрузки кармана');
        $manager->persist($event_type);
        $arr_pocket_event_type[] = $event_type;

        $event_type = new PocketEventType('r', 'Начало ремонта');
        $manager->persist($event_type);
        $arr_pocket_event_type[] = $event_type;

        $event_type = new PocketEventType('f', 'Конец ремонта');
        $manager->persist($event_type);
        $arr_pocket_event_type[] = $event_type;
        
        for ($i=0; $i < self::COUNT_EVENT; $i++) { 
            $event = new PocketEvent();

            $randomDateTimestamp = AppFixtures::randomDate();
            $drec = new DateTime();
            $drec->setTimestamp($randomDateTimestamp);
            
            $event->setDrec($drec);
            $type = $arr_pocket_event_type[array_rand($arr_pocket_event_type)];
            
            $event->setType($type);
            $event->setNumberPocket(rand(1, self::COUNT_POCKET));
            
            $manager->persist($event);
        }

        $manager->flush();
    }
}
