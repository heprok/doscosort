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
    #[Route("/", name: "app")]
    public function index()
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
}
