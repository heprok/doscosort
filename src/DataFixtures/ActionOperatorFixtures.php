<?php

namespace App\DataFixtures;

use App\Entity\ActionOperator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActionOperatorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $action = new ActionOperator( 0, 'Автоматический Стоп пильного станка кнопка СТОП :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 1, 'Автоматический Пуск пильного станка кнопка ПУСК :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 2, 'Напряжение Управления кнопка ПУСК :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 3, 'Разгрузка / Подача кнопка Автоматическое Управление :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 4, 'Разгрузка / Подача кнопка СТОП :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 5, 'Разгрузка / Подача кнопка Ручное Управление :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 6, 'Кнопка - "Грибок" - Аварийный Стоп :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 7, 'Кнопка - Сброс Аварии, Неисправности :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 8, 'Ролики Подачи Переключатель - НАЗАД :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 9, 'Ролики Подачи Переключатель - ВПЕРЕД :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 10, 'Измерительный Конвеер Переключатель - НАЗАД :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 11, 'Измерительный Конвеер Переключатель - ВПЕРЕД :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 12, 'Роторные Ролики Переключатель - Закрыты :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 13, 'Роторные Ролики Переключатель - Открыты :ОП1');
        $manager->persist($action);
        $action = new ActionOperator( 14, 'Кнопка - Роторное Центрирование :ОП1');
        $manager->persist($action);

        $manager->flush();
    }
}
