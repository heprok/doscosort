<?php

declare(strict_types=1);

namespace App\Report\Pocket;

use App\Dataset\PdfDataset;
use App\Entity\SummaryStat;
use App\Entity\SummaryStatMaterial;
use App\Report\AbstractReport;
use App\Repository\UnloadRepository;
use DatePeriod;

final class PocketUnloadReport extends AbstractReport
{
    private UnloadRepository $repository;

    public function __construct(DatePeriod $period, UnloadRepository $repository, array $people = [], array $sqlWhere = [])
    {
        $this->repository = $repository;
        $this->setLabels([
            'Время',
            '№ кармана',
            'Порода',
            'Качество',
            'Сечение',
            'Длина, мм',
            'Кол-во, шт',
            'Объём, м³',
        ]);
        parent::__construct($period, $people, $sqlWhere);
    }



        /**
     * @return SummaryStatMaterial[]
     */
    public function getSummaryStatsMaterial(): array
    {
        $summaryStatsMaterial = [];
        $summaryStatsMaterial['boards'] = new SummaryStatMaterial('Доски', $this->repository->getVolumeUnloadBoradUnloadByPeriod($this->period, $this->sqlWhere), $this->repository->getCountUnloadPocketByPeriod($this->period, $this->sqlWhere), 'м³', 'шт');
        // $summaryStatsMaterial['boards'] = new SummaryStatMaterial('Доски', '')
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
        // $precent = number_format($summaryMaterial['unloads']->getValue() / $summaryMaterial['timber']->getValue() * 100, 0);
        $countUnloadPocket = $this->repository->getCountUnloadPocketByPeriod($this->period, $this->sqlWhere); 
        $summaryStats[] = new SummaryStat('Общее кол-во выгруженных карманов', $countUnloadPocket, 'шт');

        return $summaryStats;
    }

    public function getNameReport(): string
    {
        return "выгруженных карманов";
    }

    protected function getColumnTotal(): array
    {
        return [
            $this->labels[6],
            $this->labels[7]
        ];
    }

    protected function getTextTotal(): string
    {
        return 'Общий итог{' . (string)(count($this->getLabels()) - count($this->getColumnTotal())) . '}%0{1}%1{1}';
    }

    protected function updateDataset(): bool
    {
        $unloads = $this->repository->findByPeriod($this->getPeriod(), $this->getSqlWhere());
        if (!$unloads)
            die('В данный период нет досок');
        $dataset = new PdfDataset($this->getLabels());

        foreach ($unloads as $key => $unload) {
            $drec = $unload->getDrec();
            $numberPocket = $unload->getPocket();
            $amount = $unload->getAmount();
            $qualityName = $unload->getQualities();

            $group = $unload->getGroup();
            $nameSpecies = $group->getSpecies()->getName();
            $width = $group->getWidth();
            $thickness = $group->getThickness();
            $cut = $thickness .  '×' . $width;
            $intervalLength = $group->getMinLength() . ' - ' .  $group->getMaxLength();
            $volume = $thickness * $width * $group->getMaxLength() / 1e9;

            $dataset->addRow([
                $drec->format(self::FORMAT_DATE_TIME),
                $numberPocket,
                $nameSpecies, 
                $qualityName,
                $cut,
                $intervalLength,
                $amount,
                $volume,
            ]);
        }
        $dataset->addTotal($this->getColumnTotal(), $this->getTextTotal());
        $this->addDataset($dataset);

        return true;
    }
}
