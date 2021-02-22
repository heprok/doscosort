<?php

declare(strict_types=1);

namespace App\Report\Event;

final class ActionOperatorEventReport extends AbstractEventReport
{
    public function getNameReport(): string
    {
        return "по действиям оператора";
    }

    protected function getSourceId(): array
    {
        return ['o'];
    }

    protected function getTypeId(): array
    {
        return ['a'];
    }
}
