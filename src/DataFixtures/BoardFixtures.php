<?php

namespace App\DataFixtures;

use App\Entity\Board;
use App\Entity\Length;
use App\Entity\Quality;
use App\Entity\QualityList;
use App\Entity\Species;
use App\Entity\Thickness;
use App\Entity\Width;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BoardFixtures extends Fixture implements DependentFixtureInterface
{
    const COUNT_BOARD = 10000;

    public function load(ObjectManager $manager)
    {
        $nomWidths = $manager->getRepository(Width::class)->findAll();
        $nomThickness = $manager->getRepository(Thickness::class)->findAll();
        $nomLengths = $manager->getRepository(Length::class)->findAll();
        $qualities = $manager->getRepository(Quality::class)->findAll();
        $species = $manager->getRepository(Species::class)->findAll();

        $randomDatesTimestamp = AppFixtures::getRandomDatetime(self::COUNT_BOARD);


        for ($i = 0; $i < self::COUNT_BOARD; $i++) {
            $board = new Board();

            $date = new DateTime();
            $date->setTimestamp($randomDatesTimestamp[$i]);
            $board->setDrec($date);
            $board->setWidth(rand(10, 40));
            $board->setThickness(rand(100, 600));
            $board->setLength(rand(3000, 5000));
            $board->setNomLength($nomLengths[array_rand($nomLengths)]);
            $board->setNomThickness($nomThickness[array_rand($nomThickness)]);
            $board->setNomWidth($nomWidths[array_rand($nomWidths)]);
            $board->setQuality1($qualities[array_rand($qualities)]);
            $board->setQuality2($qualities[array_rand($qualities)]);
            $board->setQuality1Name('Качество 1');
            $board->setQuality2Name('Качество 2');
            $board->setPocket(rand(0, 25));
            $board->setSpecies($species[rand(0, /* count($species) - 1 */ 2)]);
            $manager->persist($board);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SpeciesFixtures::class,
            LengthFixtures::class,
            QualityFixtures::class,
            WidthFixtures::class,
            ThicknessFixtures::class
        ];
    }
}
