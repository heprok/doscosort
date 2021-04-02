<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\Sticker;
use App\Repository\PackageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    #[Route("/ticket/{id}", name: "print_ticket")]
    public function printTicket(int $id, PackageRepository $packageRepository)
    {
        $package = $packageRepository->find($id);
        $pdf = new Sticker($package);
    }
}
