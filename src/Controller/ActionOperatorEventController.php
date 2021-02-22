<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\AbstractReport;
use App\Report\Event\ActionOperatorEventReport;
use App\Report\Event\EventPdfReport;
use App\Repository\EventRepository;
use App\Repository\PeopleRepository;
use App\Repository\ShiftRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("report/event/action_operator", name:"report_event_action_operator_")]
class ActionOperatorEventController extends AbstractController
{
    private PeopleRepository $peopleRepository;
    private EventRepository $eventRepository;

    public function __construct(PeopleRepository $peopleRepository, EventRepository $eventRepository)
    {
        $this->peopleRepository = $peopleRepository;
        $this->eventRepository = $eventRepository;
    }

    #[Route("/{start}...{end}/people/{idsPeople}/pdf", name:"for_period_with_people_show_pdf")]
    public function showReportForPeriodWithPeoplePdf(string $start, string $end, string $idsPeople  )
    {
        $request = Request::createFromGlobals();
        $sqlWhere = json_decode($request->query->get('sqlWhere') ?? '[]');
        
        $idsPeople = explode('...', $idsPeople);
        $peoples = [];
        foreach ($idsPeople as $idPeople) {
            if($idPeople != '')
                $peoples[] = $this->peopleRepository->find($idPeople);
        }
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $report = new ActionOperatorEventReport($period, $this->eventRepository, $peoples, $sqlWhere);
        $this->showPdf($report);
    }    

    #[Route("/{start}...{end}/pdf", name:"for_period_show_pdf")]
    public function showReportForPeriodPdf(string $start, string $end)
    {
        $this->showReportForPeriodWithPeoplePdf($start, $end, '');
    }

    private function showPdf(AbstractReport $report)
    {
        $report->init();
        $pdf = new EventPdfReport($report);
        $pdf->render();
    }
}
