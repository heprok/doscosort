<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ShiftRepository;
use App\Entity\Shift;
use App\Entity\People;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route("/", name:"app")]
    public function index(EventRepository $eventRepository)
    {
        // //...2020-12-15T23:59:59
        // $startDate = new \DateTime('2020-12-01T00:00:00');
        // $endDate = new \DateTime('2020-12-15T23:59:59');
        // $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate); 

        // $d = $eventRepository->findByTypeAndSourceFromPeriod($period, ['e', 's'], ['s']);
        // dump($d);
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
}
