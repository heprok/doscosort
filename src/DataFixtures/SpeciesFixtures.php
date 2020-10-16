<?php

namespace App\DataFixtures;

use App\Entity\Species;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SpeciesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $species = new Species('el', 'Ель', true, 3.5, 660, 660, 400, 500, true);
        $manager->persist($species);
        $species = new Species('so', 'Сосна', true, 3.5, 380, 1240, 400, 500, true);
        $manager->persist($species);
        $species = new Species('li', 'Лиственница', true, 2, 1200, 1200, 950, 1020, true);
        $manager->persist($species);
        $species = new Species('pi', 'Пихта', true, 4, 350, 500, 350, 450, true);
        $manager->persist($species);
        $species = new Species('ti', 'Тис', true, 2, 1200, 1200, 640, 800, true);
        $manager->persist($species);
        $species = new Species('ke', 'Кедр', true, 3.5, 380, 1240, 455,  455, true);
        $manager->persist($species);
        $species = new Species('be', 'Берёза', false, 5, 1260, 1260, 540, 700, true);
        $manager->persist($species);
        $species = new Species('bu', 'Бук', false, 5, 1300, 1300, 660, 700, true);
        $manager->persist($species);
        $species = new Species('vi', 'Вишня', false, 5, 950, 950, 490, 670, true);
        $manager->persist($species);
        $species = new Species('vz', 'Вяз', false, 4, 1350, 1350, 670, 710, true);
        $manager->persist($species);
        $species = new Species('gr', 'Груша', false, 5, null, null, 690, 800, true);
        $manager->persist($species);
        $species = new Species('du', 'Дуб', false, 2, 1360, 1360, 600, 930, true);
        $manager->persist($species);
        $species = new Species('kb', 'Карельская берёза', false, 5, 1800, 1800, 640, 800, true);
        $manager->persist($species);
        $species = new Species('kl', 'Клён', false, 5, 1450, 1450, 620, 720, true);
        $manager->persist($species);
        $species = new Species('lp', 'Липа', false, 5, 400, 400, 320, 560, true);
        $manager->persist($species);
        $species = new Species('ol', 'Ольха', false, 5, 590, 590, 380, 600, true);
        $manager->persist($species);
        $species = new Species('os', 'Осина', false, 5, 420, 420, 360, 560, true);
        $manager->persist($species);
        $species = new Species('rb', 'Рябина', false, null, 830, 830, 700, 750, true);
        $manager->persist($species);
        $species = new Species('js', 'Ясень', false, 2, 1320, 1320, 660, 700, true);
        $manager->persist($species);
        
        $manager->flush();
    }
}
