<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\PeopleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Tlc\ManualBundle\Report\Event\ActionOperatorEventReport;
use Tlc\ManualBundle\Report\Event\EventPdfReport;
use Tlc\ReportBundle\Controller\AbstractReportController;

#[Route("report/event/action_operator", name: "report_event_action_operator_")]
class ActionOperatorEventController extends AbstractReportController
{
    public function __construct(
        PeopleRepository $peopleRepository,
        private EventRepository $eventRepository
    ) {
        parent::__construct($peopleRepository);
    }

    #[Route("/pdf", name: "show_pdf")]
    public function showReportPdf()
    {
        $report = new ActionOperatorEventReport($this->period, $this->eventRepository, $this->peoples, $this->sqlWhere);
        $pdf = new EventPdfReport($report);
        $pdf->render();
    }
}
