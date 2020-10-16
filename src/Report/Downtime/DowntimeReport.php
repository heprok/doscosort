<?php

declare(strict_types=1);

namespace App\Report\Downtime;

use App\Dataset\PdfDataset;
use App\Report\AbstractReport;
use App\Repository\DowntimeRepository;
use DatePeriod;

final class DowntimeReport extends AbstractReport
{
    private DowntimeRepository $downtimeRepository;

    public function __construct(DatePeriod $period, DowntimeRepository $downtimeRepository)
    {
        $this->period = $period;
        $this->downtimeRepository = $downtimeRepository;
        $this->setLabels([
            '№',
            'Причина',
            'Место',
            'Начало',
            'Окончание',
            'Длит-ность',
        ]);
    }

    public function getNameReport(): string
    {
        return "Отчёт по простоям";
    }

    protected function updateDataset(): bool
    {
        $downtimes = $this->downtimeRepository->findByPeriod($this->getPeriod());
        if(!$downtimes)
            die('В данный период нет простоев');
        $dataset = new PdfDataset($this->getLabels());
        
        $buff['day'] = '';
        foreach ($downtimes as $key => $downtime){
            
            $cause = $downtime->getCause()->getName();
            $place = $downtime->getPlace()->getName();
            
            $startTime = $downtime->getDrec();
            $endTime = $downtime->getFinish();

            $duration  = $endTime->diff($startTime, true);
            
            if($buff['day'] != $startTime->format('d') && $key != 0)
            {
                $dataset->addSubTotal(['Длит-ность'], 'Длительность простоя за день{' . (string)(count($this->getLabels()) - 1). '}%0{1}' );
                $buff['day'] = $startTime->format('d');
            }

            $dataset->addRow([
                $key + 1,
                $cause,
                $place,
                $startTime->format(self::FORMAT_DATE_TIME),
                $endTime->format(self::FORMAT_DATE_TIME),
                $duration
            ]);

        }
        $dataset->addSubTotal(['Длит-ность'], 'Длительность простоя за день{' . (string)(count($this->getLabels()) - 1) . '}%0{1}' );
        $dataset->addTotal(['Длит-ность'], 'Общая продолжительность{' . (string)(count($this->getLabels()) - 1) . '}%0{1}' );
        

        $this->addDataset($dataset);
        
        return true;
    }
}
