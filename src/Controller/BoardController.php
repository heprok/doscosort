<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\Board\BoardPdfReport;
use App\Report\Board\BoardReport;
use App\Report\Board\RegistryBoardPdfReport;
use App\Report\Board\RegistryBoardReport;
use App\Repository\BoardRepository;
use App\Repository\PeopleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Tlc\ReportBundle\Controller\AbstractReportController;

#[Route("report/board", name: "report_board_")]
class BoardController extends AbstractReportController
{
    public function __construct(
        PeopleRepository $peopleRepository,
        private BoardRepository $boardRepository
    ) {
        parent::__construct($peopleRepository);
    }

    #[Route("/pdf", name: "show_pdf")]
    public function showReportPdf()
    {
        $report = new BoardReport($this->period, $this->boardRepository, $this->peoples, $this->sqlWhere);
        $pdf = new BoardPdfReport($report);
        $pdf->render();
    }

    #[Route("_registry/pdf", name: "registry_show_pdf`")]
    public function showReportRegistryPdf()
    {
        $report = new RegistryBoardReport($this->period, $this->boardRepository, $this->peoples, $this->sqlWhere);
        $pdf = new RegistryBoardPdfReport($report);
        $pdf->render();
    }

    // #[Route("/{start}...{end}/people/{idsPeople}/api", name: "for_period_with_people_api")]
    // public function getDataApiReport(string $start, string $end, string $idsPeople)
    // {
    //     $request = Request::createFromGlobals();
    //     $sqlWhere = json_decode($request->query->get('sqlWhere') ?? '[]');

    //     $idsPeople = explode('...', $idsPeople);
    //     $peoples = [];
    //     foreach ($idsPeople as $idPeople) {
    //         if ($idPeople != '')
    //             $peoples[] = $this->peopleRepository->find($idPeople);
    //     }
    //     $startDate = new DateTime($start);
    //     $endDate = new DateTime($end);
    //     $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
    //     $report = new BoardReport($this->period, $this->boardRepository, $this->peoples, $this->sqlWhere);
    //     $report->init();
    //     $mainDataset = $report->getMainDataset();

    //     $keysSubTotal = $mainDataset->getKeysSubTotal();
    //     $data = array_filter(
    //         $mainDataset->getData(),
    //         function ($key) use (&$keysSubTotal) {
    //             return !in_array($key, $keysSubTotal);
    //         },
    //         ARRAY_FILTER_USE_KEY
    //     );


    //     $result = [
    //         'columns' => $mainDataset->getColumns(),
    //         'data' => array_values($data),
    //     ];
    //     return $this->json($result);
    //     $this->showPdf($report);
    // }


    // #[Route("_registry/{start}...{end}/people/{idsPeople}/api", name: "registry_for_period_with_people_show_Api")]
    // public function showReportRegistryForPeriodPeopleApi(string $start, string $end, string $idsPeople)
    // {
    //     $request = Request::createFromGlobals();
    //     $sqlWhere = json_decode($request->query->get('sqlWhere') ?? '[]');

    //     $idsPeople = explode('...', $idsPeople);
    //     $peoples = [];
    //     foreach ($idsPeople as $idPeople) {
    //         if ($idPeople != '')
    //             $peoples[] = $this->peopleRepository->find($idPeople);
    //     }
    //     $startDate = new DateTime($start);
    //     $endDate = new DateTime($end);
    //     $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
    //     $report = new RegistryBoardReport($this->period, $this->boardRepository, $this->peoples, $this->sqlWhere);
    //     $report->init();
    //     $mainDataset = $report->getMainDataset();

    //     $keysSubTotal = $mainDataset->getKeysSubTotal();
    //     $data = array_filter(
    //         $mainDataset->getData(),
    //         function ($key) use (&$keysSubTotal) {
    //             return !in_array($key, $keysSubTotal);
    //         },
    //         ARRAY_FILTER_USE_KEY
    //     );


    //     $result = [
    //         'columns' => $mainDataset->getColumns(),
    //         'data' => array_values($data),
    //     ];
    //     return $this->json($result);
    //     $this->showRegistryPdf($report);
    // }
}
