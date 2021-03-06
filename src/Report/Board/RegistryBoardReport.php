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

final class RegistryBoardReport extends AbstractReport
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
        return "хронология досок";
    }

    protected function updateDataset(): bool
    {
        $boards = $this->repository->findByPeriod($this->getPeriod(), $this->getSqlWhere());
        if (!$boards)
            die('В данный период нет досок');

        $mainDataSetColumns = [
            new Column(title: 'Дата записи', precentWidth: 15, group: false, align: 'C',  total: false),
            new Column(title: 'Порода', precentWidth: 10, group: false, align: 'C',  total: false),
            new Column(title: 'Факт. толщина, мм', precentWidth: 9, group: false, align: 'C',  total: false),
            new Column(title: 'Факт. ширина, мм', precentWidth: 9, group: false, align: 'C',  total: false),
            new Column(title: 'Факт. длина, мм', precentWidth: 9, group: false, align: 'C',  total: false),
            new Column(title: 'Ном. толщина, мм', precentWidth: 9, group: false, align: 'C',  total: false),
            new Column(title: 'Ном. ширина, мм', precentWidth: 9, group: false, align: 'C',  total: false),
            new Column(title: 'Ном. длина, мм', precentWidth: 9, group: false, align: 'C',  total: false),
            new Column(title: 'Сечение', precentWidth: 5, group: false, align: 'C',  total: false),
            new Column(title: 'Объём, м³', precentWidth: 5, group: false, align: 'C',  total: false),
            new Column(title: 'Качество 1', precentWidth: 6, group: false, align: 'C',  total: false),
            new Column(title: 'Качество 2', precentWidth: 6, group: false, align: 'C',  total: false),
            new Column(title: 'Карман', precentWidth: 4, group: false, align: 'C',  total: false),
        ];

        $mainDataset = new PdfDataset(
            columns: $mainDataSetColumns,
        );

        foreach ($boards as $key => $board) {
            $drec = $board->getDrec();
            $thickness = $board->getThickness();
            $width = $board->getWidth();
            $length = $board->getLength();
            $nomThickness = $board->getNomThickness()->getNom();
            $nomWidth = $board->getNomWidth()->getNom();
            $nomLength = $board->getNomLength()->getValue();
            $quality1Name = $board->getQuality1Name();
            $quality2Name = $board->getQuality2Name();
            $pocket = $board->getPocket();
            $name_species = $board->getSpecies()->getName();
            $cut = $nomThickness .  '×' . $nomWidth;
            $volume = $nomLength * $nomThickness * $nomWidth / 1e9;

            $mainDataset->addRow([
                $drec->format(self::FORMAT_DATE_TIME),
                $name_species,
                $thickness,
                $width,
                $length,
                $nomThickness,
                $nomWidth,
                $nomLength,
                $cut,
                $volume,
                $quality1Name,
                $quality2Name,
                $pocket,
            ]);
        }

        $this->addDataset($mainDataset);

        return true;
    }
}
