<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ShiftRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/shift", name: "api_shift_")]
class ShiftApiController extends AbstractController
{
    public function __construct(
        private ShiftRepository $shiftRepository,
    ) {
    }

    #[Route('/peopleShiftOnDuration', name: "people_on_shift_for_duration")]
    public function peopleOnShiftForDuration()
    {
        $request = Request::createFromGlobals();
        $startDate = new DateTime($request->query->get('start'));
        $endDate = new DateTime($request->query->get('end'));
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        
        $peoples = $this->shiftRepository->getPeopleForByPeriod($period);
        $peoples["hydra:member"] = $peoples;
        return $this->json($peoples);
    }
}
