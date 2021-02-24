<?php

declare(strict_types=1);

namespace App\Report\Board;

use App\Dataset\PdfDataset;
use App\Entity\SummaryStat;
use App\Entity\SummaryStatMaterial;
use App\Report\AbstractReport;
use App\Repository\BoardRepository;
use DatePeriod;

final class RegistryBoardReport extends AbstractReport
{
    private BoardRepository $repository;

    public function __construct(DatePeriod $period, BoardRepository $repository, array $people = [], array $sqlWhere = [])
    {
        $this->repository = $repository;
        $this->setLabels([
            'Дата записи',
            'Порода',
            'Пил. ширина, мм',
            'Пил. длина, мм',
            'Пил. толщина, мм',
            'Ном. ширина, мм',
            'Ном. длина, мм',
            'Ном. толщина, мм',
            'Сечение',
            'Объём, м3',
            'Качество 1',
            'Качество 2',
            'Карман',
        ]);
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

    protected function getColumnTotal(): array
    {
        return [
        ];
    }


    protected function updateDataset(): bool
    {
        $boards = $this->repository->findByPeriod($this->getPeriod(), $this->getSqlWhere());
        if (!$boards)
            die('В данный период нет досок');
        $dataset = new PdfDataset($this->getLabels());

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

            $dataset->addRow([
                $drec->format(self::FORMAT_DATE_TIME),
                $name_species, 
                $thickness,
                $length,
                $width,
                $nomThickness,
                $nomLength,
                $nomWidth,
                $cut,
                $volume,
                $quality1Name,
                $quality2Name,
                $pocket,
            ]);
        }

        $this->addDataset($dataset);

        return true;
    }
}
