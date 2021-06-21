<?php

declare(strict_types=1);

namespace App\Report\Pocket;

use Tlc\ReportBundle\Report\AbstractPdf;
use Tlc\ReportBundle\Report\AbstractReport;

final class BalancePocketUnloadPdfReport extends AbstractPdf
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

    protected function getPointFontText(): int
    {
        return 8;
    }

    protected function getHeightCell(): int
    {
        return 5;
    }
}
