<?php

declare(strict_types=1);

namespace App\Report\Board;

use App\Dataset\PdfDataset;
use App\Dataset\SummaryPdfDataset;
use App\Entity\Column;
use App\Entity\SummaryStat;
use App\Entity\SummaryStatMaterial;
use App\Report\AbstractReport;
use App\Repository\BoardRepository;
use DatePeriod;

final class BoardReport extends AbstractReport
{
    public function __construct(
        DatePeriod $period,
        private BoardRepository $repository,
        array $people = [],
        array $sqlWhere = []
    ) {
        parent::__construct($period, $people, $sqlWhere);
    }

    /**
     * @return SummaryStatMaterial[]
     */
    public function getSummaryStatsMaterial(): array
    {
        $summaryStatsMaterial = [];
        $summaryStatsMaterial['boards'] = new SummaryStatMaterial(
            name: 'Доски',
            value: $this->repository->getVolumeBoardsByPeriod($this->period, $this->sqlWhere),
            count: $this->repository->getCountBoardsByPeriod($this->period, $this->sqlWhere),
            suffixValue: 'м³',
            suffixCount: 'шт'
        );
        return $summaryStatsMaterial;
    }

    /**
     *
     * @return SummaryStat[]
     */
    public function getSummaryStats(): array
    {
        $summaryStats = [];
        // $summaryMaterial = $this->getSummaryStatsMaterial();
        // $precent = number_format($summaryMaterial['boards']->getValue() / $summaryMaterial['timber']->getValue() * 100, 0);
        // $summaryStats[] = new SummaryStat('Суммарный процент выхода', $precent, '%');

        return $summaryStats;
    }

    public function getNameReport(): string
    {
        return "по доскам";
    }

    protected function updateDataset(): bool
    {
        $boards = $this->repository->getReportVolumeBoardByPeriod($this->getPeriod(), $this->getSqlWhere());
        if (!$boards)
            die('В данный период нет досок');

        $mainDatasetColumns = [
            new Column(title: 'Порода', precentWidth: 20, group: true, align: 'C', total: false),
            new Column(title: 'Качество', precentWidth: 20, group: false, align: 'C', total: false),
            new Column(title: 'Сечение, мм', precentWidth: 15, group: true, align: 'C', total: false),
            new Column(title: 'Длина, м', precentWidth: 15, group: false, align: 'C', total: false),
            new Column(title: 'Кол-во, шт', precentWidth: 15, group: false, align: 'C', total: true),
            new Column(title: 'Объем, м³', precentWidth: 15, group: false, align: 'R', total: true),
        ];

        $cutQualityDatasetColumns = [
            new Column(title: 'Качество', precentWidth: 20, group: true, align: 'C', total: false),
            new Column(title: 'Сечение, мм', precentWidth: 20, group: false, align: 'C', total: false),
            // new Column(title: 'Длина, м', precentWidth: 15, group: false, align: 'C', total: false),
            new Column(title: 'Кол-во, шт', precentWidth: 20, group: false, align: 'C', total: true),
            new Column(title: 'Объем, м³', precentWidth: 20, group: false, align: 'R', total: true),
            new Column(title: 'Процент, %', precentWidth: 20, group: false, align: 'R', total: true),
        ];

        $cutDatasetColumns = [
            new Column(title: 'Сечение, мм', precentWidth: 40, group: true, align: 'C', total: false),
            // new Column(title: 'Длина, м', precentWidth: 15, group: false, align: 'C', total: false),
            new Column(title: 'Кол-во, шт', precentWidth: 20, group: false, align: 'C', total: true),
            new Column(title: 'Объем, м³', precentWidth: 20, group: false, align: 'R', total: true),
            new Column(title: 'Процент, %', precentWidth: 20, group: false, align: 'R', total: true),
        ];

        $qualityDatasetColumns = [
            new Column(title: 'Качество, мм', precentWidth: 40, group: true, align: 'C', total: false),
            // new Column(title: 'Длина, м', precentWidth: 15, group: false, align: 'C', total: false),
            new Column(title: 'Кол-во, шт', precentWidth: 20, group: false, align: 'C', total: true),
            new Column(title: 'Объем, м³', precentWidth: 20, group: false, align: 'R', total: true),
            new Column(title: 'Процент, %', precentWidth: 20, group: false, align: 'R', total: true),
        ];

        $mainDataset = new PdfDataset(
            columns: $mainDatasetColumns,
            textTotal: 'Общий итог',
            textSubTotal: 'Итог',
        );

        $cutSummaryPdfDataset = new SummaryPdfDataset(
            nameSummary: 'Итог по сечению',
            columns: $cutDatasetColumns,
            textTotal: 'Итог',
            textSubTotal: 'Итог по сечению'
        );

        $cutQualitySummaryPdfDataset = new SummaryPdfDataset(
            nameSummary: 'Итог по качеству и сечению',
            columns: $cutQualityDatasetColumns,
            textTotal: 'Итого',
            textSubTotal: 'Итог'
        );

        $qualitySummaryPdfDataset = new SummaryPdfDataset(
            nameSummary: 'Итог по качеству',
            columns: $qualityDatasetColumns,
            textTotal: 'Итого',
            textSubTotal: 'Итог'
        );

        $buff['name_species'] = '';
        $buff['cut'] = '';
        $buff['quality_1_name'] = '';
        // $buff['cutQualitySummary'] = [];
        $totalVolume = 0;

        foreach ($boards as $key => $row) {
            $cut = $row['cut'];
            $quality_1_name = $row['quality_1_name'];
            $name_species = $row['name_species'];
            $length = number_format($row['length'] / 1000, 1);
            $count_board = $row['count_board'];
            $volume_boards = (float)$row['volume_boards'];

            if (($buff['cut'] != $cut || $buff['name_species'] != $name_species) && $key != 0) {
                $mainDataset->addSubTotal();
            }

            // if (($buff['cut'] != $cut || $buff['quality_1_name'] != $quality_1_name) && $key != 0) {

            //     $cutQualitySummaryPdfDataset->addSubTotal();
            // }

            // if ($buff['cut'] != $cut && $key != 0) {
            //     $cutSummaryPdfDataset->addSubTotal();
            // }
            
            $totalVolume += $volume_boards;

            $buff['name_species'] = $name_species;
            $buff['cut'] = $cut;
            $buff['quality_1_name'] = $quality_1_name;

            if (!isset($buff['cutSummary'][$cut])) {
                $buff['cutSummary'][$cut] = [
                    'volume' => 0,
                    'count' => 0
                ];
            }

            $buff['cutSummary'][$cut]['volume'] += $volume_boards;
            $buff['cutSummary'][$cut]['count'] += $count_board;

            if (!isset($buff['qualitySummary'][$quality_1_name])) {
                $buff['qualitySummary'][$quality_1_name] = [
                    'volume' => 0,
                    'count' => 0
                ];
            }

            $buff['qualitySummary'][$quality_1_name]['volume'] += $volume_boards;
            $buff['qualitySummary'][$quality_1_name]['count'] += $count_board;

            if (!isset($buff['cutQualitySummary'][$quality_1_name][$cut])) {
                $buff['cutQualitySummary'][$quality_1_name][$cut] = [
                    'volume' => 0,
                    'count' => 0,
                ];
            }

            $buff['cutQualitySummary'][$quality_1_name][$cut]['volume'] += $volume_boards;
            $buff['cutQualitySummary'][$quality_1_name][$cut]['count'] += $count_board;

            $mainDataset->addRow([
                $name_species,
                $quality_1_name,
                $cut,
                $length, //мм в м
                $count_board,
                $volume_boards
            ]);
        }

        foreach ($buff['cutQualitySummary'] as $quality => $cuts) {
            foreach ($cuts as $cut => $value) {
                $cutQualitySummaryPdfDataset->addRow([
                    $quality,
                    $cut,
                    $value['count'],
                    $value['volume'],
                    $value['volume'] / $totalVolume * 100,
                ]);
            }
            $cutQualitySummaryPdfDataset->addSubTotal();
        }

        foreach ($buff['qualitySummary'] as $quality => $value) {
            $qualitySummaryPdfDataset->addRow([
                $quality,
                $value['count'],
                $value['volume'],
                $value['volume'] / $totalVolume * 100,
            ]);
        }

        foreach ($buff['cutSummary'] as $cut => $value) {
            $cutSummaryPdfDataset->addRow([
                $cut,
                $value['count'],
                $value['volume'],
                $value['volume'] / $totalVolume * 100,
            ]);
        }
        // $cutSummaryPdfDataset->addSubTotal();
        $cutSummaryPdfDataset->addTotal();
        $qualitySummaryPdfDataset->addTotal();
        $cutQualitySummaryPdfDataset->addTotal();
        $mainDataset->addSubTotal();
        $mainDataset->addTotal();
        $this->addDataset($mainDataset);
        $this->addDataset($cutQualitySummaryPdfDataset);
        $this->addDataset($cutSummaryPdfDataset);
        $this->addDataset($qualitySummaryPdfDataset);

        return true;
    }
}
