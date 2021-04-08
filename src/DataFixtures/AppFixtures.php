<?php

namespace App\DataFixtures;

use DateInterval;
use DatePeriod;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const startDate = '2021-04-01';
    const endDate =  '2021-04-17';

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    static public function randomDate(): int
    {
        $min = strtotime(self::startDate);
        $max = strtotime(self::endDate);

        $val = rand($min, $max);
        return $val;
    }

    static public function getPeriod(): DatePeriod
    {
        return new DatePeriod(new DateTime(self::startDate), new DateInterval('P1D'), new DateTime(self::endDate));
    }

    /**
     * Возращает уникальные рандоманые даты
     *
     * @param integer $countDateTime
     * @return int[]
     */
    static public function getRandomDatetime(int $countDateTime): array
    {
        $randomDates = [];
        while (count($randomDates) <= $countDateTime) {
            $date = self::randomDate();
            if (!in_array($date, $randomDates)) {
                $randomDates[] = $date;
            }
        }

        return $randomDates;
    }
}
