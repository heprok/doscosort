<?php

namespace App\DataFixtures;

use App\Entity\People;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PeopleFixtures extends Fixture
{
    const COUNT_PEOPLE = 10;
    
    public function load(ObjectManager $manager)
    {
        $arr_pat = [ 'Филимонович','Демьянович','Мечиславович','Климентович', 'Олегович', 'Левович', 'Филиппович', 'Чеславович', 'Ростиславович', 'Макарович'];
        $arr_fam = ['Яхаев','Радыгин','Погребной','Цыганков','Брагин','Рекунов','Толстобров','Носачёв','Шкловский','Васенин'];
        $arr_nam = [ 'Афанасий', 'Арсений', 'Еремей', 'Клавдий', 'Евстигней', 'Рубен', 'Варфоломей', 'Саввелий', 'Евгений', 'Агап'];
        // $product = new Product();
        // $manager->persist($product);
        for ($i=0; $i < self::COUNT_PEOPLE; $i++) { 
            $people = new People($arr_fam[array_rand($arr_fam)]);
            $people->setPat($arr_pat[array_rand($arr_pat)]);
            $people->setNam($arr_nam[array_rand($arr_nam)]);
            $manager->persist($people);
        }
        
        $manager->flush();
    }
}
