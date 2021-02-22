<?php

declare(strict_types=1);

namespace App\Report\Downtime;

use App\Report\AbstractPdf;
use App\Report\AbstractReport;

final class DowntimePdfReport extends AbstractPdf
{
    public function __construct(AbstractReport $actionOperatorEventReport)
    {
        $this->setReport($actionOperatorEventReport);
        parent::__constructor('L');
    }


    protected function getPointFontHeader(): int
    {
        return 6;
    }

    protected function getColumnInPrecent(): array
    {
        return [5, 26, 26, 14, 15, 16];
    }

    protected function getAlignForColumns(): array
    {
        return ['C', 'C', 'C', 'C', 'C', 'C', 'R', 'R'];
    }

    protected function getPointFontText(): int
    {
        return 8;
    }

    protected function getHeightCell(): int
    {
        return 5;
    }
}
