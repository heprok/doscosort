<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\EventSource;
use App\Entity\EventType;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends Fixture
{
    const COUNT_EVENT = 50;

    public function load(ObjectManager $manager)
    {
        $arr_event_sources = [];
        $arr_event_type = [];

        $event_type = new EventType('e', 'Ошибка');
        $manager->persist($event_type);
        $arr_event_type[] = $event_type;

        $event_type = new EventType('m', 'Сообщение');
        $manager->persist($event_type);
        $arr_event_type[] = $event_type;

        $event_type = new EventType('a', 'Действие');
        $manager->persist($event_type);
        $arr_event_type[] = $event_type;
        
        $event_source = new EventSource('s', 'Simatic');
        $manager->persist($event_source);
        $arr_event_sources[] = $event_source;

        $event_source = new EventSource('p', 'Программа');
        $manager->persist($event_source);
        $arr_event_sources[] = $event_source;

        $event_source = new EventSource('o', 'Оператор');
        $manager->persist($event_source);
        $arr_event_sources[] = $event_source;
        
        $randomDatesTimestamp = AppFixtures::getRandomDatetime(self::COUNT_EVENT);

        for ($i=0; $i < self::COUNT_EVENT; $i++) { 
            $event = new Event();

            $drec = new DateTime();
            $drec->setTimestamp($randomDatesTimestamp[$i]);
            
            $event->setCode(rand(0, 266));
            $event->setDrec($drec);
            $type = $arr_event_type[array_rand($arr_event_type)];
            $source = $arr_event_sources[array_rand($arr_event_sources)];

            $event->setSource($source);
            $event->setType($type);

            $text = 'Текст ' . $i;

            $event->setText($text);

            $manager->persist($event);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
