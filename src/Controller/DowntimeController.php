<?php

declare(strict_types=1);

namespace App\Controller;

use Tlc\ManualBundle\Report\Downtime\DowntimePdfReport;
use Tlc\ManualBundle\Report\Downtime\DowntimeReport;
use App\Repository\DowntimeRepository;
use App\Repository\PeopleRepository;
use App\Repository\BreakSheduleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Tlc\ReportBundle\Controller\AbstractReportController;

#[Route("report/downtimes", name: "report_downtimes_")]
class DowntimeController extends AbstractReportController
{
    public function __construct(
        PeopleRepository $peopleRepository,
        private DowntimeRepository $downtimeRepository,
        private BreakSheduleRepository $breakSheduleRepository,
    ) {
        parent::__construct($peopleRepository);
    }

    #[Route("/pdf", name: "show_pdf")]
    public function showReportPdf()
    {
        $report = new DowntimeReport($this->period, $this->downtimeRepository, $this->breakSheduleRepository, $this->peoples, $this->sqlWhere);
        $pdf = new DowntimePdfReport($report);
        $pdf->render();
    }
}
