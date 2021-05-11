<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\BaseEntity;
use App\Repository\BoardRepository;
use App\Repository\PeopleRepository;
use App\Repository\PocketDistanceRepository;
use App\Repository\ShiftSheduleRepository;
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
    // private PeopleRepository $peopleRepository;
    // private ShiftRepository $unloadRepository;

    public function __construct(
        private PeopleRepository $peopleRepository,
        private ShiftRepository $shiftRepository,
        private PocketDistanceRepository $pocketDistanceRepository,
        private BoardRepository $boardRepository,
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
