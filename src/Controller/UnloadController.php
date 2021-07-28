<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\Pocket\BalancePocketUnloadPdfReport;
use App\Report\Pocket\BalancePocketUnloadReport;
use App\Report\Pocket\LastPocketUnloadPdfReport;
use App\Report\Pocket\LastPocketUnloadReport;
use App\Report\Pocket\PocketUnloadPdfReport;
use App\Report\Pocket\PocketUnloadReport;
use App\Repository\BoardRepository;
use App\Repository\PeopleRepository;
use App\Repository\PocketDistanceRepository;
use App\Repository\UnloadRepository;
use Symfony\Component\Routing\Annotation\Route;
use Tlc\ReportBundle\Controller\AbstractReportController;

#[Route("report/unload", name: "report_unload_")]
class UnloadController extends AbstractReportController
{
    public function __construct(
        PeopleRepository $peopleRepository,
        private UnloadRepository $unloadRepository,
        private PocketDistanceRepository $pocketDistanceRepository,
        private BoardRepository $boardRepository,
    ) {
        parent::__construct($peopleRepository);
    }

    #[Route("/pdf", name: "show_pdf")]
    public function showReportPdf()
    {
        $report = new PocketUnloadReport($this->period, $this->unloadRepository, $this->peoples, $this->sqlWhere);
        $pdf = new PocketUnloadPdfReport($report);
        $pdf->render();
    }

    #[Route("/lastPocket/pdf", name: "last_pocket_show_pdf")]
    public function showReportLastPocketPdf()
    {
        $lastPocket = $this->pocketDistanceRepository->getLastPocket();
        $this->sqlWhere = json_decode(
            '[{"id":"pocket","nameTable":"b.","logicalOperator":"AND","operator":"=","value":"'
                . $lastPocket->getPocket() . '"}]'
        );

        $report = new LastPocketUnloadReport($this->period, $this->boardRepository, $this->peoples, $this->sqlWhere);
        $pdf = new LastPocketUnloadPdfReport($report);
        $pdf->render();
    }

    #[Route("/balancePocket/pdf", name: "balance_pocket_show_pdf")]
    public function showReportBalancePocketPdf()
    {
        $countPocket = $this->pocketDistanceRepository->count([]);
        $report = new BalancePocketUnloadReport(
            $this->period,
            $this->boardRepository,
            $this->unloadRepository,
            $countPocket,
            $this->peoples,
            $this->sqlWhere
        );
        $pdf = new BalancePocketUnloadPdfReport($report);
        $pdf->render();
    }
}
