<?php

declare(strict_types=1);

namespace App\Chart;

use App\Dataset\ChartDataset;

class Chart
{
    const CHART_COLOR = [
        'red' => 'rgb(255, 99, 132)',
        'orange' => 'rgb(255, 159, 64)',
        'yellow' => 'rgb(255, 205, 86)',
        'green' => 'rgb(75, 192, 192)',
        'blue' => 'rgb(54, 162, 235)',
        'purple' => 'rgb(153, 102, 255)',
        'grey' => 'rgb(201, 203, 207)'
    ];

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
    public function addOption(string $name, string|bool|array $value)
    {
        $this->options[$name] = $value;
        return $this;
    }

    public function setDatasets(array $datasets)
    {
        $this->datasets = $datasets;
    }

    public function __serialize(): array
    {
        return [
            "chartdata" => [
                "labels" => $this->labels,
                "datasets" => $this->getDatasetsJson(),
            ],
            "options" => $this->options,
        ];
    }
}
