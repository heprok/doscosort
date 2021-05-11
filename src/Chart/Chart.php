<?php

declare(strict_types=1);

namespace App\Chart;

use App\Dataset\ChartDataset;

class Chart
{
    const CHART_COLOR = [
        'red' => 'rgb(255,160,122)',
        'orange' => 'rgb(244,164,96)',
        'yellow' => 'rgb(240,230,140)',
        'green' => 'rgb(75, 192, 192)',
        'blue' => 'rgb(52, 152, 219)',
        'purple' => 'rgb(147,112,219)',
        'grey' => 'rgb(189, 195, 199)',
        'fuchsia' => 'rgb(255,192,203)',
        'jotPink' => 'rgb(219,112,147)',
        'indigo' => 'rgb(75,0,130)',
        'Chartreuse' => 'rgb(127, 255, 0)',
        'DarkRed' => 'rgb(139,0,0)'

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
