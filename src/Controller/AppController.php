<?php

namespace App\Controller;

use App\Report\Event\ActionOperatorEventPdfReport;
use App\Report\Event\ActionOperatorEventReport;
use App\Repository\EventRepository;
use DatePeriod;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function index()
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
}
