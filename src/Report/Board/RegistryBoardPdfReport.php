<?php

declare(strict_types=1);

namespace App\Report\Board;

use App\Report\AbstractPdf;
use App\Report\AbstractReport;

final class RegistryBoardPdfReport extends AbstractPdf
{
    public function __construct(AbstractReport $report)
    {
        $this->setReport($report);
        parent::__constructor('L');
    }

    protected function getPointFontHeader(): int
    {
        return 6;
    }
    protected function getColumnInPrecent(): array
    {
        return [15, 10, 9, 9, 9, 9, 9, 9, 5, 5, 6, 6, 4];
    }
    
    protected function getAlignForColumns():array
    {
        return ['C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'];
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
