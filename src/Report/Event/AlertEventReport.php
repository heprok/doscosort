<?php

declare(strict_types=1);

namespace App\Report\Event;

final class AlertEventReport extends AbstractEventReport
{
    public function getNameReport(): string
    {
        return "по авариям и сообщениям";
    }

    protected function getSourceId(): array
    {
        return ['p', 's', 'o'];
    }

    protected function getTypeId(): array
    {
        return ['e', 'm'];
    }
}
