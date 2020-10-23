<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\Event\ActionOperatorEventReport;
use App\Report\Event\EventPdfReport;
use App\Repository\BoardRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("report/boards", name="report_boards")
 */
class BoardController extends AbstractController
{
    /**
     * @Route("/{start}...{end}/pdf", name="show_pdf")
     */
    public function showPdf(string $start, string $end, BoardRepository $boardRepository)
    {   
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate); 
        dd($boardRepository->findVolumeByPeriod($period));
        // $report = new ActionOperatorEventReport($period, $boardRepository);
        // $report->init();
        // $pdf = new EventPdfReport($report);
        // return $pdf->render();
    }
}
