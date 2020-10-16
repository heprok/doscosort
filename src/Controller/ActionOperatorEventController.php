<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\Event\ActionOperatorEventPdfReport;
use App\Report\Event\AbstractEventReport;
use App\Report\Event\ActionOperatorEventReport;
use App\Report\Event\EventPdfReport;
use App\Repository\EventRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("report/event/action_operator", name="report_event_action_operator_")
 */
class ActionOperatorEventController extends AbstractController
{
    /**
     * @Route("/{start}...{end}/pdf", name="show_pdf")
     */
    public function showPdf(string $start, string $end, EventRepository $eventRepository)
    {   
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate); 
        $report = new ActionOperatorEventReport($period, $eventRepository);
        $report->init();
        $pdf = new EventPdfReport($report);
        $pdf->render();
    }
}
