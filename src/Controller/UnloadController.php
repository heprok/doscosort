<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\AbstractReport;
use App\Report\Downtime\DowntimePdfReport;
use App\Report\Downtime\DowntimeReport;
use App\Report\Pocket\PocketUnloadPdfReport;
use App\Report\Pocket\PocketUnloadReport;
use App\Repository\PeopleRepository;
use App\Repository\ShiftRepository;
use App\Repository\UnloadRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("report/unload", name: "report_unload_")]
class UnloadController extends AbstractController
{
    // private PeopleRepository $peopleRepository;
    // private UnloadRepository $unloadRepository;

    public function __construct(
        private PeopleRepository $peopleRepository,
        private UnloadRepository $unloadRepository
    ) {
    }

    #[Route("/{start}...{end}/people/{idsPeople}/pdf", name: "for_period_with_people_show_pdf")]
    public function showReportForPeriodWithPeoplePdf(string $start, string $end, string $idsPeople)
    {
        $request = Request::createFromGlobals();
        $sqlWhere = json_decode($request->query->get('sqlWhere') ?? '[]');

        $idsPeople = explode('...', $idsPeople);
        $peoples = [];
        foreach ($idsPeople as $idPeople) {
            if ($idPeople != '')
                $peoples[] = $this->peopleRepository->find($idPeople);
        }
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $report = new PocketUnloadReport($period, $this->unloadRepository, $peoples, $sqlWhere);
        $this->showPdf($report);
    }

    #[Route("/{start}...{end}/pdf", name: "for_period_show_pdf")]
    public function showReportForPeriodPdf(string $start, string $end)
    {
        $this->showReportForPeriodWithPeoplePdf($start, $end, '');
    }

    private function showPdf(AbstractReport $report)
    {
        $report->init();
        $pdf = new PocketUnloadPdfReport($report);
        $pdf->render();
    }
}
