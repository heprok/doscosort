<?php

namespace App\DataFixtures;

use App\Entity\Board;
use App\Entity\Length;
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
        $qualityLists = $manager->getRepository(QualityList::class)->findAll();
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
            $board->setNomLength($nomLengths[rand(0, count($nomLengths) - 1)]);
            $board->setNomThickness($nomThickness[rand(0, count($nomThickness) - 1)]);
            $board->setNomWidth($nomWidths[rand(0, count($nomWidths) - 1)]);
            $board->setQualListId($qualityLists[rand(0, count($qualityLists) - 1)]);
            $board->setQualities(rand(0, 127));
            $board->setPocket(rand(0, 25));
            $board->setSpecies($species[rand(0, count($species) - 1)]);
            $manager->persist($board);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SpeciesFixtures::class,
            LengthFixtures::class,
            QualityListFixtures::class,
            WidthFixtures::class,
            ThicknessFixtures::class
        ];
    }
}
