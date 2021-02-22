<?php

declare(strict_types=1);

namespace App\Report\Board;

use App\Report\AbstractPdf;
use App\Report\AbstractReport;

final class BoardPdfReport extends AbstractPdf
{
    public function __construct(AbstractReport $report)
    {
        $this->setReport($report);
        parent::__constructor();
    }

    protected function getPointFontHeader(): int
    {
        return 6;
    }

    protected function getColumnInPrecent(): array
    {
        return [20, 20, 15, 15, 15, 15];
    }
    
    protected function getAlignForColumns():array
    {
        return ['C', 'C', 'C', 'C', 'C', 'R'];
    }

    protected function getPointFontText(): int
    {
        return 8;
    }
    
    protected function getHeightCell():int
    {
        return 5;
    }
}
