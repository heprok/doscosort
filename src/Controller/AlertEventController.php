<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\PeopleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Tlc\ManualBundle\Report\Event\AlertEventReport;
use Tlc\ManualBundle\Report\Event\EventPdfReport;
use Tlc\ReportBundle\Controller\AbstractReportController;

#[Route("report/event/alert", name: "report_event_alert_")]
class AlertEventController extends AbstractReportController

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
        $report = new AlertEventReport($this->period, $this->eventRepository, $this->peoples, $this->sqlWhere);
        $pdf = new EventPdfReport($report);
        $pdf->render();
    }
}
