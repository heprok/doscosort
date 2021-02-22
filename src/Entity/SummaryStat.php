<?php

declare(strict_types=1);

namespace App\Entity;

class SummaryStat
{
    private string $name;
    protected $value;
    private string $suffix;

    public function __construct(string $name, $value, string $suffix = '')
    {
        $this->name = $name;
        $this->value = $value;
        $this->suffix = $suffix;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue() 
    {
        return $this->value;
    }    
    
    public function getSuffix() : string
    {
        return $this->suffix;
    }
    
}
