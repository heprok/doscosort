<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\Sticker;
use App\Repository\UnloadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    #[Route("/ticket/{id}", name: "print_ticket")]
    public function printTicket(int $id, UnloadRepository $unloadRepository){

        $unload = $unloadRepository->findAll();
        $pdf = new Sticker($id, $unload[9]);
    }
}