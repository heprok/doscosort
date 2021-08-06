<?php

declare(strict_types=1);

namespace App\Controller;

use App\Chart\Chart;
use Tlc\ReportBundle\Dataset\ChartDataset;
use Tlc\ReportBundle\Entity\BaseEntity;
use App\Entity\Downtime;
use App\Repository\ShiftRepository;
use App\Entity\Shift;
use App\Entity\People;
use App\Repository\DowntimeRepository;
use App\Repository\BoardRepository;
use App\Repository\PeopleRepository;
use App\Repository\UnloadRepository;
use App\Repository\VarsRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeInterface;
use DoctrineExtensions\Query\Mysql\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        private PeopleRepository $peopleRepository,
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

    #[Route("/qualtites/currentShift", name: 'qualtites_current_shift')]
    public function getQualitiesOnCurrentShift()
    {
        $currentShift = $this->shiftRepository->getCurrentShift();
        if (!$currentShift)
            return $this->json(['value' => 'Не начата', 'color' => 'error'], 204);

        $qualities = $this->boardRepository->getQualityVolumeByPeriod($currentShift->getPeriod());
        $total_volume = $this->boardRepository->getVolumeBoardsByPeriod($currentShift->getPeriod());

        $labels = [''];
        $datasets = [];
        foreach ($qualities as $key => $quality) {

            $precent = round(((float)$quality['volume_boards'] / ($total_volume ?: 1) * 100), 2);
            if ($precent != 0) {
                $datasets[] = $dataset = new ChartDataset($quality['name_quality']);
                $dataset->appendData($precent);
            }
        }
        $chart = new Chart($labels, $datasets);

        // $chart->addOption('responsive', true);
        return $this->json($chart->__serialize());
    }

    #[Route("/volumeOnOperator", name: 'volume_on_operators_for_duration')]
    public function getVolumeOnOperatorForDuration(Request $request)
    {
        $period = BaseEntity::getPeriodFromArray((array)$request->query->get('period'));
        $idsOperator = $request->query->get('people'); //[1,4,6] id operator
        if (!$period || !$idsOperator) {
            $chart = new Chart(['']);
            return $this->json(['value' => 'Данные недоступны', 'color' => 'error'], 204);
        }

        $operators = $this->peopleRepository->findBy(['id' => $idsOperator]);

        $startDate = $period->getStartDate();
        $endDate = $period->getEndDate();

        //обнуляю до 1 дня текущего месяца
        $startDate->setDate((int)$startDate->format('Y'), (int)$startDate->format('m'), 1);
        $endDate->setDate((int)$endDate->format('Y'), (int)$endDate->format('m'), 1);
        $period = new DatePeriod($startDate, new DateInterval('P1M'), $endDate);
        foreach ($operators as $operator) {
            $datasets[$operator->getId()] = new ChartDataset($operator->getFio());
        }
        $labels = [];
        // foreach ($operators as $operator) {
        //     $labels[] = $operator->getFio();
        //     foreach ($period as $startDayMounth) {
        //         $end = clone $startDayMounth;
        //         $end->add(new DateInterval('P1M'));
        //         $periodWork = new DatePeriod($startDayMounth, new DateInterval('P1D'), $end);
        //         $productionOperatorOnDate = $this->boardRepository->getInfoOperatorForDuration($periodWork, $operator->getId());
        //         $datasetVolume->appendData(round((float)$productionOperatorOnDate['volume'], BaseEntity::PRECISION_FOR_FLOAT));
        //         $datasetCount->appendData($productionOperatorOnDate['count']);
        //     }
        // }

        foreach ($period as $date) {
            $end = clone $date;
            $end->add(new DateInterval('P1M'));
            $labels[] = $date->format('m.y');
            $periodWork = new DatePeriod($date, new DateInterval('P1D'), $end);
            foreach ($operators as $operator) {
                $volume = $this->boardRepository->getVolumeOnOperatorForDuration($periodWork, $operator->getId());
                $datasets[$operator->getId()]->appendData(round($volume, BaseEntity::PRECISION_FOR_FLOAT));
            }
        }

        $chart = new Chart($labels, $datasets);
        return $this->json($chart->__serialize());
    }

    #[Route("/countOnOperator", name: 'count_on_operators_for_duration')]
    public function getCountOnOperatorForDuration(Request $request)
    {
        $period = BaseEntity::getPeriodFromArray((array)$request->query->get('period'));
        $idsOperator = $request->query->get('people'); //[1,4,6] id operator
        if (!$period || !$idsOperator) {
            $chart = new Chart(['']);
            return $this->json(['value' => 'Данные недоступны', 'color' => 'error'], 204);
        }

        $operators = $this->peopleRepository->findBy(['id' => $idsOperator]);

        $startDate = $period->getStartDate();
        $endDate = $period->getEndDate();

        //обнуляю до 1 дня текущего месяца
        $startDate->setDate((int)$startDate->format('Y'), (int)$startDate->format('m'), 1);
        $endDate->setDate((int)$endDate->format('Y'), (int)$endDate->format('m'), 1);
        $period = new DatePeriod($startDate, new DateInterval('P1M'), $endDate);
        foreach ($operators as $operator) {
            $datasets[$operator->getId()] = new ChartDataset($operator->getFio());
        }
        $labels = [];
        // foreach ($operators as $operator) {
        //     $labels[] = $operator->getFio();
        //     foreach ($period as $startDayMounth) {
        //         $end = clone $startDayMounth;
        //         $end->add(new DateInterval('P1M'));
        //         $periodWork = new DatePeriod($startDayMounth, new DateInterval('P1D'), $end);
        //         $productionOperatorOnDate = $this->boardRepository->getInfoOperatorForDuration($periodWork, $operator->getId());
        //         $datasetVolume->appendData(round((float)$productionOperatorOnDate['volume'], BaseEntity::PRECISION_FOR_FLOAT));
        //         $datasetCount->appendData($productionOperatorOnDate['count']);
        //     }
        // }

        foreach ($period as $date) {
            $end = clone $date;
            $end->add(new DateInterval('P1M'));
            $labels[] = $date->format('m.y');
            $periodWork = new DatePeriod($date, new DateInterval('P1D'), $end);
            foreach ($operators as $operator) {
                $count = $this->boardRepository->getCountOnOperatorForDuration($periodWork, $operator->getId());
                $datasets[$operator->getId()]->appendData($count);
            }
        }

        $chart = new Chart($labels, $datasets);
        return $this->json($chart->__serialize());
    }

    private function getPeriodForDuration(string $duration): ?DatePeriod
    {
        switch ($duration) {
            case 'today':
                $period = BaseEntity::getPeriodForDay();
                break;

            case 'weekly':
                $period = BaseEntity::getPeriodForDay();
                $lastMonday = new DateTime('last monday ' . $period->end->format(BaseEntity::DATE_FORMAT_DB));
                $period = new DatePeriod($lastMonday, $period->getDateInterval(), $period->end);
                break;

            case 'mountly':
                $period = BaseEntity::getPeriodForDay();
                $start = $period->getStartDate();
                $start->setDate((int)$start->format('Y'), (int)$start->format('n'), 1);
                $period = new DatePeriod($start, $period->getDateInterval(), $period->getEndDate());
                break;
        }

        return $period;
    }
    #[Route('/area/volume', name: 'areac_volume_for_duration')]
    public function getChartAreaVolumeForDuration(Request $request)
    {
        if ($request->query->get('currentShift')) {
            $currentShift = $this->shiftRepository->getCurrentShift();
            if (!$currentShift)
                return $this->json(['value' => 'Не начата', 'color' => 'error'], 204);
            $period = $currentShift->getPeriod();
        } else {
            $period = BaseEntity::getWorkPeriodForPeriod(
                BaseEntity::getPeriodFromArray((array)$request->get('period'))
            );
        }
        $idsPeoples =  (array)$request->query->get('people');
        $peoples = $this->peopleRepository->findBy(['id' => $idsPeoples]);
        $shifts = $this->shiftRepository->findByPeriodWithPeople($period, $peoples);
        if (!$peoples || !$shifts) {
            return $this->json(['value' => 'Данные недоступны', 'color' => 'error'], 204);
        }
        foreach ($peoples as $people) {
            $datasets[$people->getId()] = new ChartDataset($people->getFio());
        }

        $data = [];
        foreach ($period as $day) {
            $endDay = clone $day;
            $endDay->add(new DateInterval('P1D'));
            $shifts = $this->shiftRepository->findByPeriodWithPeople(new DatePeriod($day, new DateInterval('P1D'), $endDay), $idsPeoples);
            foreach ($shifts as $shift) {
                $data[$shift->getPeople()->getId()][$day->format('d.m')][] = $this->boardRepository->getVolumeBoardsByPeriod($shift->getPeriod());
            }
        }
        $chartData = [];
        foreach ($period as $day) {
            $date = $day->format('d.m');
            $timestampDay = $day->format(BaseEntity::DATE_FORMAT_DB);
            foreach ($peoples as $people) {
                if ($data[$people->getId()][$date] ?? null)
                    $chartData[$people->getId()][$timestampDay] = [$timestampDay, round(array_sum($data[$people->getId()][$date]), BaseEntity::PRECISION_FOR_FLOAT)];
                else
                    $chartData[$people->getId()][$timestampDay] = [$timestampDay, 0];

                unset($data[$people->getId()][$date]);
            }
        }
        foreach ($datasets as $key => $dataset) {
            $dataset->setData(array_values($chartData[$key]));
        }
        $chart = new Chart([], $datasets);
        return $this->json($chart->__serialize());
    }

    #[Route("/volume/shifts", name: 'volume_for_duration')]
    public function getVolumeForDuration(Request $request)
    {
        if ($request->query->get('currentShift')) {
            $currentShift = $this->shiftRepository->getCurrentShift();
            if (!$currentShift)
                return $this->json(['value' => 'Не начата', 'color' => 'error'], 204);
            $period = $currentShift->getPeriod();
        } else {
            $period = BaseEntity::getPeriodFromArray((array)$request->get('period'));
        }

        $idsPeoples =  (array)$request->query->get('people');
        $peoples = $this->peopleRepository->findBy(['id' => $idsPeoples]);
        $shifts = $this->shiftRepository->findByPeriodWithPeople($period, $peoples);
        //ЛЮДИ СДЕЛАТЬ
        if (!$shifts || !$peoples) {
            $chart = new Chart(['']);
            return $this->json($chart->__serialize());
        }
        $datasets = [];
        foreach ($peoples as $people) {
            $datasets[$people->getId()] = new ChartDataset($people->getFio());
        }
        $labels = [];
        $data = [];
        foreach ($period as $date) {
            $end = clone $date;
            $end->add(new DateInterval('P1D'));
            $labels[] = $date->format('d.m');
            $shifts = $this->shiftRepository->findByPeriodWithPeople(new DatePeriod($date, new DateInterval('P1D'), $end), $idsPeoples);
            // foreach()
            foreach ($shifts as $shift) {
                $data[$shift->getPeople()->getId()][$date->format('d.m')][] = $this->boardRepository->getVolumeBoardsByPeriod($shift->getPeriod());
            }
        }
        $chartData = [];
        foreach ($labels as $date) {
            foreach ($peoples as $people) {
                if ($data[$people->getId()][$date] ?? null)
                    $chartData[$people->getId()][$date] = round(array_sum($data[$people->getId()][$date]), BaseEntity::PRECISION_FOR_FLOAT);
                else
                    $chartData[$people->getId()][$date] = 0;

                unset($data[$people->getId()][$date]);
            }
        }
        foreach ($datasets as $key => $dataset) {
            $dataset->setData(array_values($chartData[$key]));
        }
        $chart = new Chart($labels, $datasets);

        return $this->json($chart->__serialize());
    }
}
