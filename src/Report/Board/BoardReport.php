<?php

declare(strict_types=1);

namespace App\Report\Board;

use App\Dataset\PdfDataset;
use App\Report\AbstractReport;
use App\Repository\BoardRepository;
use DatePeriod;

final class BoardReport extends AbstractReport
{
    private BoardRepository $boardRepository;

    public function __construct(DatePeriod $period, BoardRepository $boardRepository)
    {
        $this->period = $period;
        $this->boardRepository = $boardRepository;
        $this->setLabels([
            'Порода',
            'Качество',
            'Сечение, мм',
            'Длина, м',
            'Кол-во',
            'Объем, м3',
        ]);
    }

    public function getNameReport(): string
    {
        return "Отчёт по доскам";
    }

    protected function updateDataset(): bool
    {
        // $boards = $this->boardRepository->findByPeriod($this->getPeriod());
        
        // if(!$boards)
        //     die('За данный период не было досок');

        // $dataset = new PdfDataset($this->getLabels());
        
        // $buff['day'] = '';
        // foreach ($boards as $key => $board){
            
        //     $species = $board->getSpecies()->getName();
        //     $qualities = $board->getQualities();
        //     $cut = $board->getNomThickness()->getNom() . ' × ' . $board->getNomWidth()->getNom();
        //     $nomLength = $board->getNomLength()->getValue();

        //     $startTime = $board->getDrec();
        //     $endTime = $board->getFinish();

        //     $duration  = $endTime->diff($startTime, true);
            
        //     if($buff['day'] != $startTime->format('d') && $key != 0)
        //     {
        //         $dataset->addSubTotal(['Длит-ность'], 'Длительность простоя за день{' . (string)(count($this->getLabels()) - 1). '}%0{1}' );
        //         $buff['day'] = $startTime->format('d');
        //     }

        //     $dataset->addRow([
        //         $key + 1,
        //         $cause,
        //         $place,
        //         $startTime->format(self::FORMAT_DATE_TIME),
        //         $endTime->format(self::FORMAT_DATE_TIME),
        //         $duration
        //     ]);

        // }
        // $dataset->addSubTotal(['Длит-ность'], 'Длительность простоя за день{' . (string)(count($this->getLabels()) - 1) . '}%0{1}' );
        // $dataset->addTotal(['Длит-ность'], 'Общая продолжительность{' . (string)(count($this->getLabels()) - 1) . '}%0{1}' );
        

        // $this->addDataset($dataset);
        
        return true;
    }
}
