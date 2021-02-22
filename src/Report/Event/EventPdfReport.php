<?php

declare(strict_types=1);

namespace App\Report\Event;

use App\Report\AbstractPdf;
use App\Report\AbstractReport;

final class EventPdfReport extends AbstractPdf
{

    public function __construct(AbstractReport $eventReport)
    {
        $this->setReport($eventReport);
        parent::__constructor('L');
    }

    protected function getPointFontHeader(): int
    {
        return 8;
    }

    protected function getPointFontText(): int
    {
        return 8;
    }

    protected function getAlignForColumns():array
    {
        return ['L', 'C', 'C', 'C'];
    }

    protected function getHeightCell():int
    {
        return 6;
    }

    protected function getColumnInPrecent(): array
    {
        return [70, 10, 10, 15];
    }
}
