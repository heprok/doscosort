<?php

namespace App\Doctrine\Query;

use DoctrineExtensions\Query\Postgresql\StandardLength as BaseQueryStandardLength;

final class StandardLength extends BaseQueryStandardLength
{
    protected function getSchema(): string
    {
        return "ds";
    }
}
