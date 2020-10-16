<?php

declare(strict_types=1);

namespace App\Dataset;

abstract class AbstractDataset
{
    protected array $data;
    const TIME_FORMAT = 'H:i:s';


    public function getData(): array
    {
        return $this->data;
    }
}
