<?php

declare(strict_types=1);

namespace App\Report;

use App\Dataset\AbstractDataset;
use DatePeriod;
use Exception;
use Transliterator;

abstract class AbstractReport
{
    const DECIMAL_FORMAT = 4;
    const FORMAT_DATE_TIME = 'Y.m.d H:i:s';

    private array $datasets = [];
    private array $labels = [];
    protected DatePeriod $period;

    abstract public function getNameReport(): string;
    abstract protected function updateDataset(): bool;

    public function addDataset(AbstractDataset $dataset): self
    {
        $this->datasets[] = $dataset;
        return $this;
    }

    public function getPeriod(): DatePeriod
    {
        return $this->period;
    }

    public function init(): bool
    {
        if (empty($this->datasets))
            return $this->updateDataset();

        return true;
    }

    /**
     * @return AbstractDataset[] Returns an array of AbstractDataset objects
     */
    public function getDatasets(): array
    {
        return $this->datasets;
    }

    /**
     * @return string[] Return an array string
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    public function setLabels(array $labels): self
    {
        $this->labels = $labels;
        return $this;
    }

    public function getNameReportTranslit()
    {
        $str = transliterator_transliterate('Latin-ASCII', transliterator_transliterate('Latin', $this->getNameReport()));
        $str = str_replace(' ', '_', $str);
        return $str;
    }
}
