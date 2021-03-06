<?php

declare(strict_types=1);

namespace App\Report\Board;

use App\Dataset\PdfDataset;
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

        $mainDataSetColumns = [
            new Column(title: 'Порода', precentWidth: 20, group: true, align: 'C', total: false),
            new Column(title: 'Качество', precentWidth: 20, group: false, align: 'C', total: false),
            new Column(title: 'Сечение, мм', precentWidth: 15, group: true, align: 'C', total: false),
            new Column(title: 'Длина, м', precentWidth: 15, group: false, align: 'C', total: false),
            new Column(title: 'Кол-во, шт', precentWidth: 15, group: false, align: 'C', total: true),
            new Column(title: 'Объем, м³', precentWidth: 15, group: false, align: 'R', total: true),
        ];

        $mainDataset = new PdfDataset(
            columns: $mainDataSetColumns,
            textTotal: 'Общий итог',
            textSubTotal: 'Итог',
        );

        $buff['name_species'] = '';
        $buff['cut'] = '';

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
            $buff['name_species'] = $name_species;
            $buff['cut'] = $cut;

            $mainDataset->addRow([
                $name_species,
                $quality_1_name,
                $cut,
                $length, //мм в м
                $count_board,
                $volume_boards
            ]);
        }
        $mainDataset->addSubTotal();
        $mainDataset->addTotal();
        $this->addDataset($mainDataset);

        return true;
    }
}
