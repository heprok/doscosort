<?php

declare(strict_types=1);

namespace App\Report\Pocket;

use App\Dataset\PdfDataset;
use App\Entity\Column;
use App\Entity\SummaryStat;
use App\Entity\SummaryStatMaterial;
use App\Report\AbstractReport;
use App\Repository\UnloadRepository;
use DatePeriod;

final class PocketUnloadReport extends AbstractReport
{
    public function __construct(
        DatePeriod $period,
        private UnloadRepository $repository,
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
        $summaryStatsMaterial['boards'] = new SummaryStatMaterial('Доски', $this->repository->getVolumeUnloadBoradUnloadByPeriod($this->period, $this->sqlWhere), $this->repository->getAmountUnloadBoradUnloadByPeriod($this->period, $this->sqlWhere), 'м³', 'шт');
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

    protected function updateDataset(): bool
    {
        $unloads = $this->repository->findByPeriod($this->getPeriod(), $this->getSqlWhere());
        if (!$unloads)
            die('В данный период нет досок');

        $mainDataSetColumns = [
            new Column(title: 'Время', precentWidth: 20, group: false, align: 'C', total: false),
            new Column(title: '№ кармана', precentWidth: 10, group: false, align: 'C', total: false),
            new Column(title: 'Порода', precentWidth: 13, group: false, align: 'C', total: false),
            new Column(title: 'Качество', precentWidth: 17, group: false, align: 'C', total: false),
            new Column(title: 'Сечение', precentWidth: 9, group: false, align: 'C', total: false),
            new Column(title: 'Длина, мм', precentWidth: 14, group: false, align: 'C', total: false),
            new Column(title: 'Кол-во, шт', precentWidth: 10, group: false, align: 'R', total: true),
            new Column(title: 'Объём, м³', precentWidth: 10, group: false, align: 'R', total: true),
        ];
        $mainDataset = new PdfDataset(
            columns: $mainDataSetColumns,
            textTotal: 'Общий итог',
            textSubTotal: 'Итог за день',
        );
        $buff['day'] = '';
        foreach ($unloads as $key => $unload) {
            $drec = $unload->getDrec();
            $numberPocket = $unload->getPocket();
            $amount = $unload->getAmount();
            $qualityName = $unload->getQualities();
            $volume = $unload->getVolume();

            $group = $unload->getGroup();
            $nameSpecies = $group->getSpecies()->getName();
            $width = $group->getWidth();
            $thickness = $group->getThickness();
            $cut = $thickness .  '×' . $width;
            $intervalLength = $group->getMinLength() . ' - ' .  $group->getMaxLength();

            if ($buff['day'] != $drec->format('d') && $key != 0) {
                $mainDataset->addSubTotal();
            }

            $buff['day'] = $drec->format('d');

            $mainDataset->addRow([
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
        $mainDataset->addSubTotal();
        $mainDataset->addTotal();
        $this->addDataset($mainDataset);

        return true;
    }
}
