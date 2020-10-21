<?php

namespace App\DataFixtures;

use App\Entity\Board;
use App\Entity\Length;
use App\Entity\QualityList;
use App\Entity\Thickness;
use App\Entity\Width;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BoardFixtures extends Fixture
{
    const COUNT_BOARD = 10000;

    public function load(ObjectManager $manager)
    {
        $nomWidths = $this->loadWidth($manager);
        $nomThickness = $this->loadThickness($manager);
        $nomLengths = $this->loadLength($manager);
        $qualityLists = $this->loadQualityList($manager);
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
            $manager->persist($board);

        }
        $manager->flush();
    }

    /**
     * Загружает номинальные ширины
     * И возращает их
     * @param ObjectManager $manager
     * @return Width[] 
     */
    public function loadWidth(ObjectManager $manager): array
    {
        $arrayWidth = [];

        $width = new Width(75, 75, 85);
        $manager->persist($width);
        $arrayWidth[] = $width;

        $width = new Width(100, 100, 112);
        $manager->persist($width);
        $arrayWidth[] = $width;

        $width = new Width(150, 150, 165);
        $manager->persist($width);
        $arrayWidth[] = $width;

        $manager->flush();

        return $arrayWidth;
    }

    /**
     * Загружает номинальные толщины
     * И возращает их
     * @param ObjectManager $manager
     * @return Thickness[] 
     */
    public function loadThickness(ObjectManager $manager): array
    {
        $arrayThickness = [];
        $thickness = new Thickness(25, 25, 27);
        $manager->persist($thickness);
        $arrayThickness[] = $thickness;

        $thickness = new Thickness(30, 30, 34);
        $manager->persist($thickness);
        $arrayThickness[] = $thickness;

        $thickness = new Thickness(40, 40, 45);
        $manager->persist($thickness);
        $arrayThickness[] = $thickness;

        $thickness = new Thickness(50, 50, 56);
        $manager->persist($thickness);
        $arrayThickness[] = $thickness;

        $manager->flush();
        return $arrayThickness;
    }

    /**
     * Загружает списки качесв
     * И возращает их
     * @param ObjectManager $manager
     * @return QualityList[] 
     */
    public function loadQualityList(ObjectManager $manager): array
    {
        $arrayQualityList = [];
        $qualityList = new QualityList('Основной');
        $manager->persist($qualityList);
        $arrayQualityList[] = $qualityList;

        $manager->flush();
        return $arrayQualityList;
    }

    /**
     * Загружает номинальные длины
     * И возращает их
     * @param ObjectManager $manager
     * @return Length[] 
     */
    public function loadLength(ObjectManager $manager): array
    {
        $arrayLength = [];
        $length = new Length(3000);
        $manager->persist($length);
        $arrayLength[] = $length;

        $length = new Length(3300);
        $manager->persist($length);
        $arrayLength[] = $length;

        $length = new Length(3600);
        $manager->persist($length);
        $arrayLength[] = $length;

        $length = new Length(3900);
        $manager->persist($length);
        $arrayLength[] = $length;

        $length = new Length(4200);
        $manager->persist($length);
        $arrayLength[] = $length;

        $length = new Length(4500);
        $manager->persist($length);
        $arrayLength[] = $length;

        $length = new Length(4800);
        $manager->persist($length);
        $arrayLength[] = $length;

        $length = new Length(5100);
        $manager->persist($length);
        $arrayLength[] = $length;

        $length = new Length(5400);
        $manager->persist($length);
        $arrayLength[] = $length;

        $length = new Length(5700);
        $manager->persist($length);
        $arrayLength[] = $length;

        $length = new Length(6000);
        $manager->persist($length);
        $arrayLength[] = $length;

        $manager->flush();

        return $arrayLength;
    }
}
