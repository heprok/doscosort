<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\AbstractReport;
use App\Report\Board\BoardPdfReport;
use App\Report\Board\BoardReport;
use App\Report\Board\RegistryBoardPdfReport;
use App\Report\Board\RegistryBoardReport;
use App\Repository\BoardRepository;
use App\Repository\PeopleRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("report/board", name:"report_board_")]
class BoardController extends AbstractController
{
    private PeopleRepository $peopleRepository;
    private BoardRepository $boardRepository;

    public function __construct(PeopleRepository $peopleRepository, BoardRepository $boardRepository)
    {
        $this->peopleRepository = $peopleRepository;
        $this->boardRepository = $boardRepository;
    }

    #[Route("/{start}...{end}/people/{idsPeople}/pdf", name:"for_period_with_people_show_pdf")]
    public function showReportForPeriodWithPeoplePdf(string $start, string $end, string $idsPeople)
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
        $report = new BoardReport($period, $this->boardRepository, $peoples, $sqlWhere);
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
        $pdf = new BoardPdfReport($report);
        $pdf->render();
    }

    #[Route("_registry/{start}...{end}/people/{idsPeople}/pdf", name:"registry_for_period_with_people_show_pdf")]
    public function showReportRegistryForPeriodPeoplePdf(string $start, string $end, string $idsPeople  )
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
        $report = new RegistryBoardReport($period, $this->boardRepository, $peoples, $sqlWhere);
        $this->showRegistryPdf($report);
    }    

    #[Route("_registry/{start}...{end}/pdf", name:"registry_for_period_show_pdf")]
    public function showReportRegistryForPeriodPdf(string $start, string $end)
    {
        $this->showReportRegistryForPeriodPeoplePdf($start, $end, '');
    }

    private function showRegistryPdf(AbstractReport $report)
    {
        $report->init();
        $pdf = new RegistryBoardPdfReport($report);
        $pdf->render();
    }
}
