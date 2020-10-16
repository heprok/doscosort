<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\Downtime\DowntimePdfReport;
use App\Report\Downtime\DowntimeReport;
use App\Repository\DowntimeRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("report/downtimes", name="report_downtimes_")
 */
class DowntimeController extends AbstractController
{
    /**
     * @Route("/{start}...{end}/pdf", name="show_pdf")
     */
    public function showPdf(string $start, string $end, DowntimeRepository $downtimeRepository)
    {   
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate); 
        $report = new DowntimeReport($period, $downtimeRepository);
        $report->init();
        $pdf = new DowntimePdfReport($report);
        $pdf->render();
    }
}
