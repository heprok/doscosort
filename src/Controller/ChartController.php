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

        $labels = [];
        $datasets = [];
        foreach ($qualities as $key => $quality) {
            
            $precent = round(((float)$quality['volume_boards'] / ($total_volume ?: 1) * 100), 2);
            if ($precent != 0) {
                $labels[] = $quality['name_quality'];
                $datasets[] = $dataset = new ChartDataset();
                $dataset->appendData($precent);
            }
        }
        $chart = new Chart($labels, $datasets);

        // $chart->addOption('responsive', true);
        return $this->json($chart->__serialize());
    }

    #[Route("/volumeOnOperator", name: 'volumes_on_operators_for_duration')]
    public function getVolumeOnOperatorForDuration()
    {
        $request = Request::createFromGlobals();

        $idsOperator = $request->query->get('operators'); //[1,4,6] id operator
        $duration = json_decode($request->query->get('duration'), true);

        if (!$duration || !$idsOperator) {
            $chart = new Chart(['']);
            $chart->addOption('responsive', true);
            $chart->addOption('elements', ['bar' => ['borderWidth' => 1]]);

            $chart->addOption('maintainAspectRatio', false);
            return $this->json($chart->__serialize());
        }

        $operators = $this->peopleRepository->findBy(['id' => $idsOperator]);

        $startDate = new DateTime($duration['start']);
        $endDate = new DateTime($duration['end']);

        //обнуляю до 1 дня текущего дня
        $startDate->setDate((int)$startDate->format('Y'), (int)$startDate->format('m'), 1);
        $endDate->setDate((int)$endDate->format('Y'), (int)$endDate->format('m'), 1);
        $period = new DatePeriod($startDate, new DateInterval('P1M'), $endDate);

        $colors = Chart::CHART_COLOR;
        $datasets = [];
        foreach ($operators as $operator) {
            $datasets[$operator->getId()] = new ChartDataset($operator->getFio(), array_shift($colors));
        }

        $labels = [];
        $data = [];
        foreach ($period as $date) {
            $end = clone $date;
            $end->add(new DateInterval('P1M'));
            $labels[] = $date->format('m.y');
            $period = new DatePeriod($date, new DateInterval('P1D'), $end);
            foreach ($operators as $operator) {
                $data[$operator->getId()][$date->format('m.y')][] = round($this->boardRepository->getVolumeOnOperatorForDuration($period, $operator->getId()), BaseEntity::PRECISION_FOR_FLOAT);
            }
        }
        $chartData = [];
        foreach ($labels as $date) {
            foreach ($operators as $operator) {
                if ($data[$operator->getId()][$date] ?? null)
                    $chartData[$operator->getId()][$date] = round(array_sum($data[$operator->getId()][$date]), BaseEntity::PRECISION_FOR_FLOAT);
                else
                    $chartData[$operator->getId()][$date] = 0;

                unset($data[$operator->getId()][$date]);
            }
        }

        foreach ($datasets as $key => $dataset) {
            $dataset->setData(array_values($chartData[$key]));
        }
        $chart = new Chart($labels, $datasets);
        $chart->addOption('responsive', false);
        $chart->addOption('elements', ['bar' => ['borderWidth' => 1]]);
        $chart->addOption('maintainAspectRatio', false);
        return $this->json($chart->__serialize());
    }

    #[Route("/countOnOperator", name: 'count_on_operators_for_duration')]
    public function getCountOnOperatorForDuration()
    {
        $request = Request::createFromGlobals();

        $idsOperator = $request->query->get('operators'); //[1,4,6] id operator
        $duration = json_decode($request->query->get('duration'), true);

        if (!$duration || !$idsOperator) {
            $chart = new Chart(['']);
            $chart->addOption('responsive', true);
            $chart->addOption('elements', ['bar' => ['borderWidth' => 1]]);

            $chart->addOption('maintainAspectRatio', false);
            return $this->json($chart->__serialize());
        }

        $operators = $this->peopleRepository->findBy(['id' => $idsOperator]);

        $startDate = new DateTime($duration['start']);
        $endDate = new DateTime($duration['end']);

        //обнуляю до 1 дня текущего дня
        $startDate->setDate((int)$startDate->format('Y'), (int)$startDate->format('m'), 1);
        $endDate->setDate((int)$endDate->format('Y'), (int)$endDate->format('m'), 1);
        $period = new DatePeriod($startDate, new DateInterval('P1M'), $endDate);

        $colors = Chart::CHART_COLOR;
        $datasets = [];
        foreach ($operators as $operator) {
            $datasets[$operator->getId()] = new ChartDataset($operator->getFio(), array_shift($colors));
        }

        $labels = [];
        $data = [];
        foreach ($period as $date) {
            $end = clone $date;
            $end->add(new DateInterval('P1M'));
            $labels[] = $date->format('m.y');
            $period = new DatePeriod($date, new DateInterval('P1D'), $end);
            foreach ($operators as $operator) {
                $data[$operator->getId()][$date->format('m.y')][] = round($this->boardRepository->getCountOnOperatorForDuration($period, $operator->getId()), BaseEntity::PRECISION_FOR_FLOAT);
            }
        }
        $chartData = [];
        foreach ($labels as $date) {
            foreach ($operators as $operator) {
                if ($data[$operator->getId()][$date] ?? null)
                    $chartData[$operator->getId()][$date] = round(array_sum($data[$operator->getId()][$date]), BaseEntity::PRECISION_FOR_FLOAT);
                else
                    $chartData[$operator->getId()][$date] = 0;

                unset($data[$operator->getId()][$date]);
            }
        }

        foreach ($datasets as $key => $dataset) {
            $dataset->setData(array_values($chartData[$key]));
        }
        $chart = new Chart($labels, $datasets);
        $chart->addOption('responsive', false);
        $chart->addOption('elements', ['bar' => ['borderWidth' => 1]]);
        $chart->addOption('maintainAspectRatio', false);
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

    #[Route("/volume/shifts/{duration}", requirements: ["duration" => "mountly|weekly"], name: 'volume_for_duration')]
    public function getVolumeForDuration(string $duration)
    {
        $period = $this->getPeriodForDuration($duration);
        // $period = new DatePeriod(new DateTime('2021-03-19'), new DateInterval('P1D'), new DateTime('2021-03-26'));
        $shifts = $this->shiftRepository->findByPeriod($period);
        $peoples = $this->shiftRepository->getPeopleForByPeriod($period);
        if (!$shifts || !$peoples) {
            $chart = new Chart(['']);
            $chart->addOption('responsive', true);
            $chart->addOption('elements', ['bar' => ['borderWidth' => 1]]);

            $chart->addOption('maintainAspectRatio', false);
            return $this->json($chart->__serialize());
        }
        $colors = Chart::CHART_COLOR;
        $datasets = [];
        foreach ($peoples as $people) {
            $datasets[$people->getId()] = new ChartDataset($people->getFio(), array_shift($colors));
        }
        $labels = [];
        $data = [];
        foreach ($period as $date) {
            $end = clone $date;
            $end->add(new DateInterval('P1D'));
            $labels[] = $date->format('d.m');
            $shifts = $this->shiftRepository->findByPeriod(new DatePeriod($date, new DateInterval('P1D'), $end));
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

        $chart->addOption('responsive', false);
        $chart->addOption('elements', ['bar' => ['borderWidth' => 1]]);
        // $chart->addOption('legend', [
        //     'position' => 'right'
        // ]);
        $chart->addOption('maintainAspectRatio', false);
        return $this->json($chart->__serialize());
    }
}
