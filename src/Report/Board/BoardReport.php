<?php

declare(strict_types=1);

namespace App\Report\Board;

use App\Dataset\PdfDataset;
use App\Entity\SummaryStat;
use App\Entity\SummaryStatMaterial;
use App\Report\AbstractReport;
use App\Repository\BoardRepository;
use DatePeriod;

final class BoardReport extends AbstractReport
{
    private BoardRepository $repository;

    public function __construct(DatePeriod $period, BoardRepository $repository, array $people = [], array $sqlWhere = [])
    {
        $this->repository = $repository;
        $this->setLabels([
            'Порода',
            'Качество',
            'Сечение, мм',
            'Длина, м',
            'Кол-во, шт',
            'Объем, м3',
        ]);
        parent::__construct($period, $people, $sqlWhere);
    }

        /**
     * @return SummaryStatMaterial[]
     */
    public function getSummaryStatsMaterial(): array
    {
        $summaryStatsMaterial = [];
        $summaryStatsMaterial['boards'] = new SummaryStatMaterial('Доски', $this->repository->getVolumeBoardsByPeriod($this->period), $this->repository->getCountBoardsByPeriod($this->period), 'м³', 'шт');

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

    protected function getColumnTotal(): array
    {
        return [
            $this->labels[4],
            $this->labels[5]
        ];
    }

    protected function getTextSubTotal(string $name_species, $cut): string
    {
        return 'Итог (' . $name_species . ', '  . $cut . '){' . (string)(count($this->getLabels()) - count($this->getColumnTotal())) . '}%0{1}%1{1}';
    }

    protected function getTextTotal(): string
    {
        return 'Общий итог{' . (string)(count($this->getLabels()) - count($this->getColumnTotal())) . '}%0{1}%1{1}';
    }
    public function getNameReport(): string
    {
        return "по доскам";
    }

    protected function getTextSubTotal(string $name_species, $cut): string
    {
        return 'Итог (' . $name_species . ','  . $cut . '){' . (string)(count($this->getLabels()) - count($this->getColumnTotal())) . '}%0{1}%1{1}';
    }

    protected function getTextTotal(): string
    {
        return 'Общий итог{' . (string)(count($this->getLabels()) - count($this->getColumnTotal())) . '}%0{1}%1{1}';
    }

    protected function getColumnTotal(): array
    {
        return [
            $this->labels[4],
            $this->labels[5]
        ];
    }


    protected function updateDataset(): bool
    {
        $boards = $this->repository->getReportVolumeBoardByPeriod($this->getPeriod(), $this->getSqlWhere());
        if (!$boards)
            die('В данный период нет досок');
        $dataset = new PdfDataset($this->getLabels());
        // $buff['quality_1_name'] = -1;
        $buff['name_species'] = '';
        $buff['cut'] = '';
        // $buff['length'] = '';
        foreach ($boards as $key => $row) {
            $cut = $row['cut'];
            $quality_1_name = $row['quality_1_name'];
            $name_species = $row['name_species'];
            $length = $row['length'] / 1000;
            $count_board = $row['count_board'];
            $volume_boards = (float)$row['volume_boards'];

            if (($buff['cut'] != $cut || $buff['name_species'] != $name_species) && $key != 0) {
                $dataset->addSubTotal($this->getColumnTotal(), $this->getTextSubTotal($buff['name_species'], $buff['cut']));
            }
            $buff['name_species'] = $name_species;
            $buff['cut'] = $cut;
            // $buff['quality_1_name'] = $quality_1_name;
            // $buff['length'] = $length;
            
            $dataset->addRow([
                $name_species, 
                $quality_1_name,
                $cut,
                $length, //мм в м
                $count_board,
                $volume_boards
            ]);
        }

        $dataset->addSubTotal($this->getColumnTotal(), $this->getTextSubTotal($buff['name_species'], $buff['cut']));
        $dataset->addTotal($this->getColumnTotal(), $this->getTextTotal());

        $this->addDataset($dataset);

        return true;
    }
}
