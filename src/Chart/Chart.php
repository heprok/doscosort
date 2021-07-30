<?php

declare(strict_types=1);

namespace App\Chart;

use Tlc\ReportBundle\Dataset\ChartDataset;

class Chart
{
    public function __construct(
        private array $labels,
        private array $datasets = [],
        private array $options = [],
    ) {
    }

    public function addDataset(ChartDataset $dataset)
    {
        $this->datasets[] = $dataset;
        return $this;
    }
    private function getDatasetsJson()
    {
        $result = [];
        foreach ($this->datasets as $dataset) {
            $result[] = $dataset->__serialize();
        }
        return $result;
    }

    public function setDatasets(array $datasets)
    {
        $this->datasets = $datasets;
    }

    public function __serialize(): array
    {
        return [
            "labels" => $this->labels,
            "datasets" => $this->getDatasetsJson(),
            // "options" => $this->options,
        ];
    }
}
