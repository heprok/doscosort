<?php

declare(strict_types=1);

namespace App\Report\Downtime;

use App\Dataset\PdfDataset;
use App\Entity\People;
use App\Entity\Shift;
use App\Report\AbstractReport;
use App\Repository\DowntimeRepository;
use DatePeriod;
use Doctrine\DBAL\Driver\PDO\Exception;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class DowntimeReport extends AbstractReport
{
    private DowntimeRepository $downtimeRepository;

    /**
     *
     * @param DatePeriod $period
     * @param DowntimeRepository $downtimeRepository
     * @param People[] $peoples
     */
    public function __construct(DatePeriod $period, DowntimeRepository $downtimeRepository, array $peoples = [], array $sqlWhere = [])
    {
        $this->downtimeRepository = $downtimeRepository;
        $this->setLabels([
            '№',
            'Причина',
            'Место',
            'Начало',
            'Окончание',
            'Длит-ность',
            ]);
        parent::__construct($period, $peoples, $sqlWhere);
    }

    public function getNameReport(): string
    {
        return "по простоям";
    }

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

    protected function getColumnTotal(): array
    {
        return [
            $this->labels[5]
        ];
    }

    protected function getTextSubTotal(): string
    {
        return 'Длительность простоя за день{' . (string)(count($this->getLabels()) - count($this->getColumnTotal())) . '}%0{1}';
    }

    protected function getTextTotal(): string
    {
        return 'Общая продолжительность{' . (string)(count($this->getLabels()) - count($this->getColumnTotal())) . '}%0{1}';
    }

    protected function updateDataset(): bool
    {
        $downtimes = $this->downtimeRepository->findByPeriod($this->getPeriod(), $this->getSqlWhere());

        if (!$downtimes)
            die('В данный период нет простоев');
        $dataset = new PdfDataset($this->getLabels());

        $buff['day'] = '';
        foreach ($downtimes as $key => $downtime) {

            $cause = $downtime->getCause();
            $place = $downtime->getPlace();

            $startTime = $downtime->getDrec();
            $endTime = $downtime->getFinish();

            $duration  = $endTime ? $endTime->diff($startTime, true) : 'Продолжается';

            if ($buff['day'] != $startTime->format('d') && $key != 0) {
                $dataset->addSubTotal($this->getColumnTotal(), $this->getTextSubTotal());
            }
            
            $buff['day'] = $startTime->format('d');
            $dataset->addRow([
                $key + 1,
                $cause,
                $place,
                $startTime->format(self::FORMAT_DATE_TIME),
                $endTime ? $endTime->format(self::FORMAT_DATE_TIME) : 'Продолжается',
                $duration
            ]);
        }
        $dataset->addSubTotal($this->getColumnTotal(), $this->getTextSubTotal());
        $dataset->addTotal($this->getColumnTotal(), $this->getTextTotal());

        $this->addDataset($dataset);

        return true;
    }
}
