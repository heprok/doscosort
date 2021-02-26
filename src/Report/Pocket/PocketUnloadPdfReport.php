<?php

declare(strict_types=1);

namespace App\Report\Pocket;

use App\Report\AbstractPdf;
use App\Report\AbstractReport;

final class PocketUnloadPdfReport extends AbstractPdf
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
        return [20, 10, 13, 17, 9, 14, 10, 10];
    }
    
    protected function getAlignForColumns():array
    {
        return ['C', 'C', 'C', 'C', 'C', 'C', 'R', 'R'];
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
