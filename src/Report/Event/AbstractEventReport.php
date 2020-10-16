<?php

declare(strict_types=1);

namespace App\Report\Event;

use App\Dataset\PdfDataset;
use App\Report\AbstractReport;
use App\Repository\EventRepository;
use DatePeriod;

abstract class AbstractEventReport extends AbstractReport
{
    private EventRepository $eventRepository;

    public function __construct(DatePeriod $period, EventRepository $eventRepository)
    {
        $this->period = $period;
        $this->eventRepository = $eventRepository;
        $this->setLabels([
            'Событие',
            'Источник',
            'Тип',
            'Время'
        ]);
    }

    abstract protected function getSourceId():array;
    abstract protected function getTypeId():array;
    abstract public function getNameReport(): string;
    // {
    //     return "Отчёт по действиям оператора";
    // }

    protected function updateDataset(): bool
    {
        $events = $this->eventRepository->findByTypeAndSourceFromPeriod($this->getPeriod(), $this->getTypeId(), $this->getSourceId());
        
        $dataset = new PdfDataset($this->getLabels());

        foreach ($events as $event){
            
            $text = $event->getText();
            $source = $event->getSource()->getName();
            $type = $event->getType()->getName();
            
            $time = $event->getDrec()->format(parent::FORMAT_DATE_TIME);

            $dataset->addRow([
                $text,
                $source,
                $type,
                $time,
            ]);

        }

        $this->addDataset($dataset);
        
        return true;
    }
}
