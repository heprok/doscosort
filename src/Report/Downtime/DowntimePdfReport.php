<?php

declare(strict_types=1);

namespace App\Report\Downtime;

use App\Report\AbstractPdf;
use App\Report\AbstractReport;
use DateInterval;

final class DowntimePdfReport extends AbstractPdf
{
    const COLOR_GRAY = 238;
    const POINT_FONT_HEADER = 10;
    const POINT_FONT_TEXT = 10;

    public function __construct(AbstractReport $actionOperatorEventReport)
    {
        $this->setReport($actionOperatorEventReport);
        parent::__constructor();
    }

    /**
     * Задаёт размеры для столбца, указывать в процентах
     * В сумме должно быть 100
     * @return array
     */
    protected function getPuntForColumns(): array
    {
        $widthColumnInPercent = [5, 15, 15, 25, 25, 15];
        $widthColumnsInPunt = [];

        foreach ($widthColumnInPercent as $widthColumn) {
            $widthColumnsInPunt[] = ($this->getPageWidth() - self::MARGIN_LEFT - self::MARGIN_LEFT) * $widthColumn / 100;
        }

        return $widthColumnsInPunt;
    }

    public function render()
    {
        return $this->Output($this->getNameFile());
    }

    /**
     * Рисует данные в таблице
     *
     * @param string[] $header
     * @param PdfDataset[] $data
     * @return void
     */
    protected function paintTable(array $header, array $data)
    {
        $count_dataset = count($data);
        $count_labels = count($this->report->getLabels());
        $puntColumns = $this->getPuntForColumns();
        // Colors, line width and bold font
        $this->SetFillColor(self::COLOR_GRAY);
        $this->SetTextColor(0);
        // $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B', self::POINT_FONT_HEADER);
        // Header
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($puntColumns[$i], self::HEIGH_CELL, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('', '', self::POINT_FONT_TEXT);
        // Data
        $fill = 0;
        for ($i = 0; $i < $count_dataset; $i++) {
            $keys_sub_total = $data[$i]->getKeysSubTotal();
            $data = $data[$i]->getData();
            foreach ($data as $key => $row) {
                if (!in_array($key, $keys_sub_total)) {
                    for ($j = 0; $j < $count_labels; $j++) {
                        if ($row[$j] instanceof DateInterval) {
                            $this->Cell($puntColumns[$j], self::HEIGH_CELL, $row[$j]->format(self::TIME_FORMAT_FOR_INTERVAL), 1, 0, 'C', 0);
                        } else{
                            $this->Cell($puntColumns[$j], self::HEIGH_CELL, $row[$j], 1, 0, 'C', 0);
                        }
                    }
                    $this->Ln();
                } else {
                    $reg = '/([а-яА-Яa-zA-Z\s\d\(\)\.\:]+){(\d)}/um';
                    preg_match_all($reg, $row, $matches, PREG_SET_ORDER, 0);
                    $buff['oldWidth'] = 0;
                    foreach ($matches as $match) {
                        $rowspan = $match[2];
                        $text = $match[1];
                        if ($rowspan >= 2) {
                            $widthColumn = $this->getWidthColumnForSpan((int)$rowspan) + 0.5;
                            $this->Cell($widthColumn, self::HEIGH_CELL, $text, 1, 0, 'R', 1);
                            $buff['oldWidth'] += $widthColumn;
                        } else
                            $this->Cell(array_sum($puntColumns) - $buff['oldWidth'], self::HEIGH_CELL, $text, 1, 0, 'C', 1);

                    }
                    $this->Ln();
                }
            }
            $this->setPage(1);
            $this->SetY(self::MARGIN_LEFT + 10);
        }
        $this->Cell(array_sum($puntColumns), 0, '', 'T');
    }
}
