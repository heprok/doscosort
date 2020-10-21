<?php

namespace App\Report;

use DatePeriod;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use TCPDF;

abstract class AbstractPdf extends TCPDF
{
    protected AbstractReport $report;
    const DATE_FORMAT = 'Y.m.d H:i:s';
    const TIME_FORMAT_FOR_INTERVAL = '%H:%I:%S';
    const DATE_FORMAT_FOR_DOWNLOAD = 'Y-m-d H:i';
    const MARGIN_LEFT = 20;
    const MARGIN_TOP = 20;
    const HEIGH_CELL = 10;
    const WIDTH_LOGO = 14;
    
    abstract protected function getPuntForColumns();
    abstract protected function paintTable(array $header, array $data);
    
    public function __constructor(
        string $orientation = 'P',
        string $unit = 'mm',
        string $format = 'A4',
        bool $unicode = true,
        string $encoding = 'UTF-8',
        bool $diskcache = false,
        bool $pdfa = false
    ) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
        $this->SetCreator('TechoLesCom');
        $this->SetMargins(self::MARGIN_LEFT, self::MARGIN_TOP);
        $this->SetAutoPageBreak(true, 20);

        $datasets = $this->report->getDatasets();

        $this->AddPage();
        $this->SetFont('dejavusans', '', 11);
        $this->SetXY(self::MARGIN_LEFT, self::MARGIN_TOP);
        $this->paintTable($this->report->getLabels(), $datasets);
    }

    public function render()
    {
        return $this->Output($this->getNameFile());
    }

    public function header()
    {
        $package = new Package(new EmptyVersionStrategy());
        // dd($package->getUrl('build/logo.png'));
        $this->SetFont('dejavusans', '', 14);
        $image_file = $package->getUrl('build/images/logosmall.svg');
        $this->ImageSVG($image_file, self::MARGIN_LEFT, 3, self::WIDTH_LOGO, 15, 'www.techno-les.com', 'L', false, 0, 0);
        $this->setX(self::WIDTH_LOGO + self::MARGIN_LEFT + 15);
        $this->Cell(0, 20, $this->getNameReport(), 0, 0, 'L', 0, '',  0, false, 'М', 'М');
        $this->Ln(10, true);
        $this->SetFont('dejavusans', '', 10);
        $this->SetY(self::MARGIN_TOP / 2 / 2, false);
        $this->Cell($this->getPageWidth() - self::MARGIN_LEFT - self::MARGIN_LEFT / 2, 0, 'с ' . $this->getPeriod()->getStartDate()->format(self::DATE_FORMAT), 0, 0, 'R', 0, '',  0, false, 'М', 'М');
        $this->ln();
        $this->SetY(self::MARGIN_TOP / 2, false);
        $this->Cell($this->getPageWidth() - self::MARGIN_LEFT - self::MARGIN_LEFT / 2, 0, 'до ' . $this->getPeriod()->getEndDate()->format(self::DATE_FORMAT),0, 0, 'R', 0, '',  0, false, 'М', 'М');
        // $this->SetY(29);
        // $this->SetLineStyle(array('width' => 2, 'color' => ['#fff']));
        // $this->Line(20, 29, $this->getPageWidth() - 20, $this->getPageHeight() - 20 );
    }

    public function footer()
    {
        $this->SetY(-15);
        $this->SetFont('dejavusans', 'I', 8);
        $this->Cell(0, 10, 'Страница ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function setNameReport(string $name_report)
    {
        $this->name_report = $name_report;
    }

    protected function setReport(AbstractReport $report):self
    {
        $this->report = $report;
        return $this;
    }
    public function getNameReport(): string
    {
        return $this->report->getNameReport();
    }

    public function getPeriod(): DatePeriod
    {
        return $this->report->getPeriod();
    }

    protected function getNameFile(): string
    {
        return $this->report->getNameReportTranslit() . '_' . $this->report->getPeriod()->getStartDate()->format(self::DATE_FORMAT_FOR_DOWNLOAD) . '.pdf';
    }

    protected function getWidthColumnForSpan(int $rowspan):int
    {
        $puntColumns = $this->getPuntForColumns();
        $result = 0;
        for($i = 0; $i < $rowspan; $i++)
        {
            $result += $puntColumns[$i];
        }
        return $result;
    }
}