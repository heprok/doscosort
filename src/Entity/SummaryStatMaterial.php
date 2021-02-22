<?php

declare(strict_types=1);

namespace App\Entity;

class SummaryStatMaterial extends SummaryStat
{
    private int $count;
    private string $suffixCount;
    private int $precision;

    public function __construct(string $name, $value,  int $count, string $suffixValue = '', string $suffixCount = '', int $precision = 3)
    {
        parent::__construct($name, $value, $suffixValue);
        $this->suffixCount = $suffixCount;
        $this->count = $count;
        $this->precision = $precision;
    }

    public function getValue()
    {
        return round($this->value, $this->precision);
    }
    public function getCount() :int
    {
        return $this->count;
    }

    public function getSuffixCount() : string
    {
        return $this->suffixCount;
    }
}   
