<?php

declare(strict_types=1);

namespace App\Controller;

use App\Chart\Chart;
use App\Dataset\ChartDataset;
use App\Entity\BaseEntity;
use App\Entity\Downtime;
use App\Repository\ShiftRepository;
use App\Entity\Shift;
use App\Entity\People;
use App\Repository\DowntimeRepository;
use App\Repository\BoardRepository;
use App\Repository\UnloadRepository;
use App\Repository\VarsRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeInterface;
use DoctrineExtensions\Query\Mysql\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/charts", name: "charts_")]
class ChartController extends AbstractController
{

    public function __construct(
        private ShiftRepository $shiftRepository,
        private BoardRepository $boardRepository,
        private DowntimeRepository $downtimeRepository,
        private VarsRepository $varsRepository,
        private UnloadRepository $unloadRepository,
    ) {
    }

    #[Route("/currentShift", name: "currentShift")]
    public function getCurrentShift()
    {
        $currentShift = $this->shiftRepository->getCurrentShift();
        if (!$currentShift)
            return $this->json(['value' => 'Не начата', 'color' => 'error'], 204);

        return $this->json([
            'value' => $currentShift->getPeople()->getFio(),
            'subtitle' => 'Смена № ' . $currentShift->getNumber(),
            'color' => 'info'
        ]);
    }

    #[Route("/qualtites/currentShift", name: 'qualtites_chart_current_shift')]
    public function getQualitiesOnCurrentShift()
    {
        $currentShift = $this->shiftRepository->getCurrentShift();
        if (!$currentShift)
            return $this->json(['value' => 'Не начата', 'color' => 'error'], 204);

        $qualities = $this->boardRepository->getQualityVolumeByPeriod($currentShift->getPeriod());
        $total_volume = $this->boardRepository->getVolumeBoardsByPeriod($currentShift->getPeriod());

        $labels = [];
        $datasets = [];
        $data = [];
        // foreach ($qualities as $key => $quality) {
        //     $datasets[] = $dataset = new ChartDataset($quality['name_quality']);
        //     $dataset->setData([(int)((float)$quality['volume_boards'] / $total_volume * 100)]);
        //     $dataset->setBackgroundColor(Chart::CHART_COLOR[array_rand(Chart::CHART_COLOR)]);
        //     // $labels[] = $quality['name_quality'];
        // }
        foreach ($qualities as $quality) {
            $precent = (int)((float)$quality['volume_boards'] / $total_volume * 100);
            if ($precent != 0) {

                $labels[] = $quality['name_quality'];
                $data[] = $precent;
            }
        }
        $dataset = new ChartDataset('Объём, %', Chart::CHART_COLOR['red'], Chart::CHART_COLOR['orange']);
        $dataset->setData($data);
        $chart = new Chart($labels, [$dataset]);

        $chart->addOption('responsive', true);
        $chart->addOption('indexAxis', 'y',);
        $chart->addOption('elements', ['bar' => ['borderWidth' => 1]]);
        $chart->addOption(
            'legend',
            [
                'position' => 'right'
            ],
        );
        $chart->addOption(
            'title',
            [
                'display' => true,
                'text' => 'Распределение по качеству'
            ]
        );

        $chart->addOption('maintainAspectRatio', false);
        return $this->json($chart->__serialize());
    }
}
