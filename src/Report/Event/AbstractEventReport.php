<?php

declare(strict_types=1);

namespace App\Report\Event;

use App\Dataset\PdfDataset;
use App\Entity\Shift;
use App\Report\AbstractReport;
use App\Repository\EventRepository;
use DatePeriod;

abstract class AbstractEventReport extends AbstractReport
{
    private EventRepository $eventRepository;

    /**
     *
     * @param DatePeriod $period
     * @param EventRepository $eventRepository
     * @param People[] $people
     */
    public function __construct(DatePeriod $period, EventRepository $eventRepository, array $people = [], array $sqlWhere = [])
    {
        $this->eventRepository = $eventRepository;
        $this->setLabels([
            'Событие',
            'Источник',
            'Тип',
            'Время'
        ]);
        parent::__construct($period, $people, $sqlWhere);
    }

    abstract protected function getSourceId():array;
    abstract protected function getTypeId():array;
    abstract public function getNameReport(): string;
    // {
    //     return "Отчёт по действиям оператора";
    // }

    /**
     * @return SummaryStat[]
     */
    public function getSummaryStats(): array
    {
        return [];
    }

    /**
     * @return SummaryStatMaterial[]
     */
    public function getSummaryStatsMaterial(): array
    {
        return [];
    }

    protected function updateDataset(): bool
    {
        $events = $this->eventRepository->findByTypeAndSourceFromPeriod($this->getPeriod(), $this->getTypeId(), $this->getSourceId(), $this->getSqlWhere());
        $dataset = new PdfDataset($this->getLabels());

        foreach ($events as $event){
            
            $text = $event->getText();
            $source = $event->getSource();
            $type = $event->getType();
            
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
