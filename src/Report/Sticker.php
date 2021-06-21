<?php

declare(strict_types=1);

namespace App\Report;

use Tlc\ReportBundle\Entity\BaseEntity;
use App\Entity\Package;
use TCPDF;

class Sticker extends TCPDF
{
    const PDF_MARGIN_LEFT = 10;
    const PDF_MARGIN_RIGHT = 10;
    const PDF_MARGIN_TOP = 13;

    const PUNT_HEADER_UP = 30;
    const PUNT_HEADER_UP_NUMBER = 20;
    const PUNT_HEADER_BLACK_ROW = 50;
    const PUNT_HEADER_DOWN = 35;

    // const WIDTH_PAGE=

    public function __construct(
        private Package $package
    ) {
        parent::__construct('L', 'mm', 'A5');

        // set margins
        $this->SetMargins(self::PDF_MARGIN_LEFT, self::PDF_MARGIN_TOP, self::PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(0);
        $this->SetFooterMargin(0);
        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $this->SetAutoPageBreak(false, 0);

        // set default font subsetting mode
        // $this->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $this->SetFont('montserratb', '', self::PUNT_HEADER_UP, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $this->AddPage();
        $widthPage = $this->getPageWidth() - self::PDF_MARGIN_LEFT - self::PDF_MARGIN_RIGHT;
        $heightPage = $this->getPageHeight() - self::PDF_MARGIN_TOP * 2;

        // $group = $package->getGroup();
        $cut = $package->getThickness() . 'x' . $package->getWidth();
        $intrvalLength = $package->getRangeLengths();
        $number = $package->getId();
        $volume = number_format($package->getVolume(), BaseEntity::PRECISION_FOR_FLOAT);
        $count = $package->getCount();
        $speciesName = $package->getSpecies()->getName();
        // $speciesName = 'Лиственница';
        $qualities = $package->getQualities();
        // $qualities = 'NK,GR';
        $blockHeight =  $heightPage / 3;
        $widthBlock = $widthPage / 6;

        $style4 = array('L' => 0);
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,20,5,10', 'phase' => 10, 'color' => array(255, 0, 0));
        $this->Rect(0, $blockHeight * 2 - self::PDF_MARGIN_TOP * 2 - 2, $widthPage + self::PDF_MARGIN_RIGHT + self::PDF_MARGIN_LEFT, $blockHeight, 'DF', $style4,);

        // set text shadow effect
        // $this->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

        //up block
        $this->Cell($widthBlock * 3, $blockHeight,  $speciesName, border: 0);
        $this->Cell($widthBlock * 2, $blockHeight, str_replace(',', ' ', $qualities), border: 0, align: 'L');
        $this->SetFontSize(self::PUNT_HEADER_UP_NUMBER);
        $this->Cell($widthBlock * 1, $blockHeight, '№ ' . $number, border: 0, align: 'R');
        $this->Ln();

        $this->SetFontSize(self::PUNT_HEADER_BLACK_ROW);
        $this->SetTextColor(255);
        $this->Cell($widthBlock * 3, $blockHeight, $cut, border: 0, align: '');
        $this->Cell($widthBlock * 2, $blockHeight, $intrvalLength, border: 0, align: 'L');


        $this->Ln();
        $this->SetFontSize(self::PUNT_HEADER_DOWN);
        $this->SetTextColor(0);
        $this->Cell($widthBlock * 2, $blockHeight,  $count . ' шт.', border: 0,);
        $this->Cell($widthBlock * 2, $blockHeight, $volume . ' м³', border: 0, align: 'C');


        // set style for barcode
        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        // QRCODE,L : QR-CODE Low error correction
        // $this->setXY(0, 0);
        // $this->write2DBarcode('www.tcpdf.org', 'QRCODE,L', 20, 30, 50, 50, $style, 'N');
        // dd($_SERVER['HTTP_REFERER'] . 'ticket/' . $package->getId() );
        $this->write2DBarcode((string)$number, 'QRCODE,L', x: $widthBlock * 3 + $widthBlock * 2 + self::PDF_MARGIN_RIGHT, y: $blockHeight * 2 + self::PDF_MARGIN_TOP, w: $widthBlock * 4, h: $blockHeight - self::PDF_MARGIN_TOP / 4  + 3, style: $style, align: 'R');
        // $this->Text(x: $widthBlock * 3, y: $blockHeight * 3, txt: 'QRCODE L');

        // $this->Cell($widthBlock, $blockHeight, '№ ' . $number, border: 0: align: 'C');

        // Print text using writeHTMLCell()
        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $this->Output('example_001.pdf', 'I');
    }

    public function header()
    {
    }
    public function footer()
    {
    }
}
