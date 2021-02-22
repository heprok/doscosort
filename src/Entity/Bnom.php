<?php

namespace App\Entity;

class Bnom
{
  private int $thickness;
  private int $width;

  public function __construct(int $thickness, int $width)
  {
    $this->thickness = $thickness;
    $this->width = $width;
  }

  public function __toString()
  {
    return "($this->thickness,$this->width)";
  }
}
