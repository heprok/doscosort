<?php

declare(strict_types=1);

namespace App\Report\Pocket;

use App\Dataset\PdfDataset;
use App\Dataset\SummaryPdfDataset;
use App\Entity\BaseEntity;
use App\Entity\Column;
use App\Entity\SummaryStat;
use App\Entity\SummaryStatMaterial;
use App\Report\AbstractReport;
use App\Repository\BoardRepository;
use App\Repository\UnloadRepository;
use DateInterval;
use DatePeriod;

final class BalancePocketUnloadReport extends AbstractReport
{

    private $summaryStatsMaterial = [];

    public function __construct(
        DatePeriod $period,
        private BoardRepository $boardRepository,
        private UnloadRepository $unloadRepository,
        private int $countPocket,
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
        $summaryStatsMaterial = $this->summaryStatsMaterial;

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

        return $summaryStats;
    }

    public function getNameReport(): string
    {
        return "по остаткам в карманах";
    }

    protected function updateDataset(): bool
    {
        // $unloads = $this->repository->findByPeriod($this->getPeriod(), $this->getSqlWhere());
        // if (!$unloads)
        // die('В данный период нет досок');

        $mainDataSetColumns = [
            new Column(title: '№ кармана', precentWidth: 10, group: false, align: 'C', total: false),
            new Column(title: 'Порода', precentWidth: 20, group: false, align: 'C', total: false),
            new Column(title: 'Качество', precentWidth: 20, group: false, align: 'C', total: false),
            new Column(title: 'Сечение', precentWidth: 15, group: false, align: 'C', total: false),
            new Column(title: 'Длина, мм', precentWidth: 15, group: false, align: 'C', total: false),
            new Column(title: 'Кол-во, шт', precentWidth: 10, group: false, align: 'R', total: true),
            new Column(title: 'Объём, м³', precentWidth: 10, group: false, align: 'R', total: true),
        ];
        $mainDataset = new PdfDataset(
            columns: $mainDataSetColumns,
            textTotal: 'Общий итог',
            textSubTotal: 'Итог за день',
        );

        $summaryDataSetColumns = [
            new Column(title: 'Порода', precentWidth: 20, group: true, align: 'C', total: false),
            new Column(title: 'Длина, мм', precentWidth: 20, group: true, align: 'C', total: false),
            new Column(title: 'Сечение', precentWidth: 20, group: false, align: 'C', total: false),
            new Column(title: 'Кол-во, шт', precentWidth: 20, group: false, align: 'R', total: true),
            new Column(title: 'Объём, м³', precentWidth: 20, group: false, align: 'R', total: true),
        ];


        $summaryDataset = new SummaryPdfDataset(
            nameSummary: 'Сводка по карманам',
            columns: $summaryDataSetColumns,
            textTotal: 'Общий итог',
            textSubTotal: 'Итог',
        );
        
        $buff['day'] = '';
        $totalCount = 0;
        $totalVolume = 0;

        $buff['summaryUnload'] = [];

        for ($i = 1; $i <= $this->countPocket; $i++) {
            $sqlWhere = json_decode(/* $request->query->get('sqlWhere') ?? */
                '[{"id":"pocket","nameTable":"u.","logicalOperator":"AND","operator":"=","value":"'
                    . $i . '"}]'
            );
            $row = [
                'pocket' => $i,
                'name_species' => [],
                'quality_1_name' => [],
                'cut' => [],
                'length' => [],
                'count_boards' => 0,
                'volume_boards' => 0.0
            ];
            $unload = $this->unloadRepository->findLastUnload($this->getPeriod(), $sqlWhere);
            if ($unload) {
                $period = new DatePeriod($unload->getDrec(), new DateInterval('P1D'), $this->getPeriod()->getEndDate());
                $sqlWhere = json_decode(
                    '[{"id":"pocket","nameTable":"b.","logicalOperator":"AND","operator":"=","value":"'
                        . $i . '"}]'
                );
                $boardsByQualties = $this->boardRepository->getReportVolumeBoardByPeriod($period, $sqlWhere);

                foreach ($boardsByQualties as $boardReport) {
                    $row['name_species'][] = $species = $boardReport['name_species'];
                    $row['quality_1_name'][] = $boardReport['quality_1_name'];
                    $row['length'][] = $length = $boardReport['length'];
                    $row['cut'][] = $cut = $boardReport['cut'];
                    $row['count_boards'] += $boardReport['count_board'];
                    $row['volume_boards'] += round((float)$boardReport['volume_boards'], BaseEntity::PRECISION_FOR_FLOAT);

                    if($buff['summaryUnload'][$species][$length][$cut] ?? null )
                    {
                        $buff['summaryUnload'][$species][$length][$cut]['volume'] += round((float)$boardReport['volume_boards'], BaseEntity::PRECISION_FOR_FLOAT);
                        $buff['summaryUnload'][$species][$length][$cut]['count'] += $boardReport['count_board'];
                    }
                    else{
                        $buff['summaryUnload'][$species][$length][$cut]['volume'] = round((float)$boardReport['volume_boards'], BaseEntity::PRECISION_FOR_FLOAT);
                        $buff['summaryUnload'][$species][$length][$cut]['count'] = $boardReport['count_board'];
                    }
                }
                
            }
            $row['name_species'] = implode(', ', array_unique($row['name_species']));
            $row['quality_1_name'] = implode(', ', array_unique($row['quality_1_name']));
            $row['length'] = implode(', ', array_unique($row['length']));
            $row['cut'] = implode(', ', array_unique($row['cut']));

            $totalCount += $row['count_boards'];
            $totalVolume += $row['volume_boards'];
            $mainDataset->addRow(array_values($row));
        }   
        
        $this->summaryStatsMaterial['boards'] = new SummaryStatMaterial(
            name: 'Доски в карманах',
            value: $totalVolume,
            count: $totalCount,
            suffixValue: 'м³',
            suffixCount: 'шт'
        );
     
        foreach ($buff['summaryUnload'] as $species => $lengths) {
            foreach ($lengths as $length => $cuts) {
                foreach ($cuts as $cut => $value) {
                    $summaryDataset->addRow([
                        $species,
                        $length,
                        $cut,
                        $value['count'],
                        $value['volume'],
                    ]);
                }
                $summaryDataset->addSubTotal();
            }
        }

        // foreach ($unloads as $key => $unload) {
        //     $stLength = $unload[1];
        //     $unload = $unload[0];
        //     $drec = $unload->getDrec();
        //     $numberPocket = $unload->getPocket();
        //     $amount = $unload->getAmount();
        //     $qualityName = $unload->getQualities();
        //     $volume = $unload->getVolume();

        //     $group = $unload->getGroup();
        //     $nameSpecies = $group->getSpecies()->getName();
        //     $width = $group->getWidth();
        //     $thickness = $group->getThickness();
        //     $cut = $thickness .  '×' . $width;
        //     $intervalLength = $group->getIntervalLength();

        //     if ($buff['day'] != $drec->format('d') && $key != 0) {
        //         $mainDataset->addSubTotal();
        //     }

        //     if (!isset($buff['summaryUnload'][$nameSpecies][$stLength][$cut])) {
        //         $buff['summaryUnload'][$nameSpecies][$stLength][$cut] = [
        //             'volume' => 0,
        //             'count' => 0,
        //         ];
        //     }

        //     $buff['summaryUnload'][$nameSpecies][$stLength][$cut]['volume'] += $volume;
        //     $buff['summaryUnload'][$nameSpecies][$stLength][$cut]['count'] += $amount;


        //     $buff['day'] = $drec->format('d');

        //     $mainDataset->addRow([
        //         $drec->format(self::FORMAT_DATE_TIME),
        //         $numberPocket,
        //         $nameSpecies,
        //         $qualityName,
        //         $cut,
        //         $intervalLength,
        //         $amount,
        //         $volume,
        //     ]);
        // }



        // $mainDataset->addSubTotal();
        $mainDataset->addTotal();
        // $summaryDataset->addTotal();
        $this->addDataset($mainDataset);
        $this->addDataset($summaryDataset);
        return true;
    }
}
